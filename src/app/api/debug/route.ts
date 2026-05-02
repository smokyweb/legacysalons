import { NextResponse } from 'next/server'

export async function GET() {
  return NextResponse.json({
    has_admin_password: !!process.env.ADMIN_PASSWORD,
    admin_password_length: process.env.ADMIN_PASSWORD?.length || 0,
    node_env: process.env.NODE_ENV,
  })
}
