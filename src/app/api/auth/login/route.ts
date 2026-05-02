import { NextRequest, NextResponse } from 'next/server'
import { signToken, COOKIE } from '../../../../lib/auth'

const USERS: Record<string, { password: string; role: string; displayName: string }> = {
  admin: { password: process.env.ADMIN_PASSWORD || 'admin123', role: 'admin', displayName: 'Admin' },
  employee: { password: process.env.EMPLOYEE_PASSWORD || 'employee123', role: 'employee', displayName: 'Employee' },
}

export async function POST(req: NextRequest) {
  const { username, password } = await req.json()
  const user = USERS[username?.toLowerCase()]
  if (!user || password !== user.password) {
    return NextResponse.json({ error: 'Invalid username or password' }, { status: 401 })
  }
  const token = await signToken({ role: user.role, username: username.toLowerCase(), displayName: user.displayName, ts: Date.now() })
  const res = NextResponse.json({ success: true, role: user.role, displayName: user.displayName })
  res.cookies.set(COOKIE, token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 7,
    path: '/',
  })
  return res
}
