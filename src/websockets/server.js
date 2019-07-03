'use strict'

 const path = require('path')
const DeepstreamServer = require('deepstream.io')
const C = DeepstreamServer.constants
const env = 'dev'
const BASE_URL_ENV = {
  'dev': 'http://localhost',
}

 const options = {
  logLevel: 'DEBUG',
  connectionEndpoints: {
    http: {
      options: {
        port: 9292
      }
    }
  }
}
 const server = new DeepstreamServer(options)

 server.start()