#!/bin/sh
# Ensure /data dir exists and has correct permissions
mkdir -p /data
# Start the Next.js server
exec node server.js
