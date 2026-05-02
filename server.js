// Custom server.js for Next.js standalone — ensures runtime env vars are loaded
process.env.HOSTNAME = process.env.HOSTNAME || '0.0.0.0'
process.env.PORT = process.env.PORT || '3000'

const { createServer } = require('http')
const { parse } = require('url')
const next = require('next')

const dev = process.env.NODE_ENV !== 'production'
const app = next({ dev })
const handle = app.getRequestHandler()

app.prepare().then(() => {
  createServer((req, res) => {
    const parsedUrl = parse(req.url, true)
    handle(req, res, parsedUrl)
  }).listen(process.env.PORT, process.env.HOSTNAME, () => {
    console.log(`> Ready on http://${process.env.HOSTNAME}:${process.env.PORT}`)
    console.log(`> ADMIN_PASSWORD set: ${!!process.env.ADMIN_PASSWORD}`)
  })
})
