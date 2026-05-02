import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'Legacy Salons — Payment Dashboard',
  description: 'Weekly payment report dashboard',
}

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body className="bg-slate-900 min-h-screen text-white antialiased">{children}</body>
    </html>
  )
}
