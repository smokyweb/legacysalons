import Database from 'better-sqlite3'
import path from 'path'
import fs from 'fs'

const DB_PATH = process.env.DB_PATH || './legacysalons.db'

// Ensure directory exists
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
  `)
  return _db
}
