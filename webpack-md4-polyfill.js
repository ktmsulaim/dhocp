const crypto = require('crypto');

const origCreateHash = crypto.createHash;

crypto.createHash = (algorithm, options) =>
    origCreateHash(algorithm === 'md4' ? 'sha256' : algorithm, options);
