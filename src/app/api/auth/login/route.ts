import { NextRequest, NextResponse } from 'next/server'
import { signToken, COOKIE } from '../../../../lib/auth'

export async function POST(req: NextRequest) {
  const { password } = await req.json()
  
  // Read password from env — fallback to 'admin123' if not set (dev only)
  const adminPassword = process.env.ADMIN_PASSWORD || 'admin123'
  
  if (!password || password !== adminPassword) {
    return NextResponse.json({ error: 'Invalid password' }, { status: 401 })
  }
  
  const token = await signToken({ role: 'admin', ts: Date.now() })
  const res = NextResponse.json({ success: true })
  res.cookies.set(COOKIE, token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 7,
    path: '/',
  })
  return res
}
