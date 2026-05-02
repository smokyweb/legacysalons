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
      last_contacted INTEGER
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
  return _db
}
