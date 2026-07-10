import crypto from 'crypto';

export function canonical(method: string, pathAndQuery: string, body: string, ts: string) {
  const bodyHash = crypto.createHash('sha256').update(body || '').digest('hex');
  return `${method.toUpperCase()}\n${pathAndQuery}\n${bodyHash}\n${ts}`;
}

export function sign(secret: string, canonical: string) {
  return crypto.createHmac('sha256', secret).update(canonical).digest('base64');
}
