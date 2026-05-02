import { redirect } from 'next/navigation'
import { getSession } from '../../lib/auth'
import DashboardClient from './DashboardClient'

export default async function DashboardPage() {
  const session = await getSession()
  if (!session) redirect('/login')
  const safeSession = { role: session?.role as string, displayName: session?.displayName as string, username: session?.username as string }
  return <DashboardClient session={safeSession} />
}
