import Database from 'better-sqlite3'
import path from 'path'
import fs from 'fs'

const DB_PATH = process.env.DB_PATH || './legacysalons.db'

const dir = path.dirname(path.resolve(DB_PATH))
if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true })

let _db: Database.Database | null = null

export function getDb(): Database.Database {
  if (_db) return _db
  _db = new Database(path.resolve(DB_PATH))
  _db.pragma('journal_mode = WAL')

  _db.exec(`
    -- ===== RENT MANAGEMENT TABLES =====

    CREATE TABLE IF NOT EXISTS settings (
      key TEXT PRIMARY KEY,
      value TEXT NOT NULL,
      description TEXT,
      editable INTEGER DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS tenants (
      tenant_id TEXT PRIMARY KEY,
      suite TEXT NOT NULL,
      first_name TEXT,
      last_name TEXT,
      tenant_name TEXT,
      weekly_rent REAL DEFAULT 0,
      start_date TEXT,
      end_date TEXT,
      status TEXT DEFAULT 'Vacant',
      phone TEXT,
      email TEXT,
      license_exp TEXT,
      license_status TEXT,
      contract_status TEXT,
      notes TEXT,
      created_at INTEGER DEFAULT (unixepoch() * 1000),
      updated_at INTEGER DEFAULT (unixepoch() * 1000)
    );

    CREATE TABLE IF NOT EXISTS rent_payments (
      payment_id TEXT PRIMARY KEY,
      tenant_id TEXT NOT NULL,
      payment_date TEXT NOT NULL,
      amount REAL NOT NULL,
      payment_type TEXT,
      fee REAL DEFAULT 0,
      net_amount REAL,
      reference TEXT,
      rent_week_start TEXT,
      posted_by TEXT,
      confidence TEXT,
      notes TEXT,
      created_at INTEGER DEFAULT (unixepoch() * 1000),
      FOREIGN KEY (tenant_id) REFERENCES tenants(tenant_id)
    );

    CREATE TABLE IF NOT EXISTS free_weeks (
      free_week_id TEXT PRIMARY KEY,
      tenant_id TEXT NOT NULL,
      type TEXT,
      weeks_granted INTEGER DEFAULT 0,
      date_granted TEXT,
      apply_to_week_start TEXT,
      weeks_used INTEGER DEFAULT 0,
      approval_status TEXT DEFAULT 'Pending',
      approved_by TEXT,
      notes TEXT,
      created_at INTEGER DEFAULT (unixepoch() * 1000),
      FOREIGN KEY (tenant_id) REFERENCES tenants(tenant_id)
    );

    CREATE TABLE IF NOT EXISTS rent_schedule (
      rent_id TEXT PRIMARY KEY,
      tenant_id TEXT NOT NULL,
      week_start TEXT NOT NULL,
      week_end TEXT NOT NULL,
      rent_due_date TEXT NOT NULL,
      weekly_rent REAL DEFAULT 0,
      free_week_credit REAL DEFAULT 0,
      rent_charge REAL DEFAULT 0,
      payments_applied REAL DEFAULT 0,
      balance_before_late_fee REAL DEFAULT 0,
      days_late INTEGER DEFAULT 0,
      late_fee REAL DEFAULT 0,
      total_due REAL DEFAULT 0,
      status TEXT DEFAULT 'No Charge',
      FOREIGN KEY (tenant_id) REFERENCES tenants(tenant_id)
    );

    CREATE TABLE IF NOT EXISTS ai_automation_log (
      import_id TEXT PRIMARY KEY,
      email_date TEXT,
      from_address TEXT,
      subject TEXT,
      extracted_tenant TEXT,
      extracted_amount REAL,
      extracted_payment_type TEXT,
      extracted_payment_date TEXT,
      confidence TEXT,
      review_status TEXT DEFAULT 'Needs Review',
      posted_payment_id TEXT,
      raw_snippet TEXT,
      ai_notes TEXT,
      created_at INTEGER DEFAULT (unixepoch() * 1000)
    );

    -- ===== PAYMENT TRACKING TABLES =====

    CREATE TABLE IF NOT EXISTS runs (
      id TEXT PRIMARY KEY,
      created_at INTEGER NOT NULL,
      status TEXT NOT NULL DEFAULT 'running',
      payment_count INTEGER DEFAULT 0,
      total_amount REAL DEFAULT 0,
      week_start TEXT,
      week_end TEXT
    );
    CREATE TABLE IF NOT EXISTS payments (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      run_id TEXT NOT NULL,
      name TEXT,
      amount REAL,
      date TEXT,
      payment_app TEXT,
      FOREIGN KEY (run_id) REFERENCES runs(id)
    );
    CREATE TABLE IF NOT EXISTS contacts (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      created_at INTEGER NOT NULL DEFAULT (unixepoch() * 1000),
      updated_at INTEGER NOT NULL DEFAULT (unixepoch() * 1000),
      first_name TEXT NOT NULL,
      last_name TEXT,
      email TEXT,
      phone TEXT,
      company TEXT,
      stage TEXT NOT NULL DEFAULT 'New Lead',
      notes TEXT,
      assigned_to TEXT,
      deal_value REAL DEFAULT 0,
      last_contacted INTEGER,
      likely_move_date TEXT,
      budget TEXT,
      speciality TEXT,
      lead_source TEXT,
      lead_date TEXT
    );
    CREATE TABLE IF NOT EXISTS contact_activity (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      contact_id INTEGER NOT NULL,
      created_at INTEGER NOT NULL DEFAULT (unixepoch() * 1000),
      type TEXT NOT NULL,
      content TEXT,
      status TEXT DEFAULT 'sent',
      FOREIGN KEY (contact_id) REFERENCES contacts(id)
    );
  `)

  // Seed default settings if empty
  const settingsCount = (_db.prepare('SELECT COUNT(*) as c FROM settings').get() as {c: number}).c
  if (settingsCount === 0) {
    const insertSetting = _db.prepare('INSERT OR IGNORE INTO settings (key, value, description, editable) VALUES (?, ?, ?, ?)')
    const seedSettings = _db.transaction(() => {
      insertSetting.run('late_fee_per_day', '20', 'Daily fee applied after Saturday due date when balance remains', 1)
      insertSetting.run('card_fee_pct', '5', 'Processing fee percentage for card payments', 1)
      insertSetting.run('model_start_sunday', '2025-10-19', 'First Sunday of rent model', 1)
      insertSetting.run('weeks_to_generate', '12', 'Starter number of weeks generated per tenant', 1)
      insertSetting.run('balance_threshold', '0.01', 'Late fee only when balance exceeds threshold', 1)
    })
    seedSettings()
  }

  // Migration: add new columns to existing contacts table if missing
  const existingCols = (_db.prepare("PRAGMA table_info(contacts)").all() as Array<{name: string}>).map(c => c.name)
  const newCols: Array<{name: string; def: string}> = [
    { name: 'likely_move_date', def: 'TEXT' },
    { name: 'budget', def: 'TEXT' },
    { name: 'speciality', def: 'TEXT' },
    { name: 'lead_source', def: 'TEXT' },
    { name: 'lead_date', def: 'TEXT' },
  ]

  // Migration: add location column to tenants table
  const tenantCols = (_db.prepare('PRAGMA table_info(tenants)').all() as Array<{name: string}>).map(c => c.name)
  if (!tenantCols.includes('location')) {
    _db.exec("ALTER TABLE tenants ADD COLUMN location TEXT DEFAULT 'Village'")
  }
  for (const col of newCols) {
    if (!existingCols.includes(col.name)) {
      _db.exec(`ALTER TABLE contacts ADD COLUMN ${col.name} ${col.def}`)
    }
  }

  return _db
}
