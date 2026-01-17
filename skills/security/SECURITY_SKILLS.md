# 🔒 Security Skills - Best Practices

## 🎯 OWASP Top 10 Quick Reference

### 1. Broken Access Control
```typescript
// ✅ Verify permissions
export async function deletePost(id: string, userId: string) {
  const post = await getPost(id);
  
  if (post.authorId !== userId) {
    throw new UnauthorizedError('Cannot delete others\' posts');
  }
  
  await db.posts.delete(id);
}

// ❌ Trust client data
// const userId = req.body.userId; // Client can fake this!
```

### 2. Cryptographic Failures
```typescript
// ✅ Hash passwords
import bcrypt from 'bcrypt';

const hashedPassword = await bcrypt.hash(password, 10);

// ✅ Use HTTPS everywhere
// ✅ Encrypt sensitive data at rest
```

### 3. Injection
```php
// ✅ Prepared statements
global $wpdb;
$results = $wpdb->prepare(
    "SELECT * FROM users WHERE email = %s",
    $email
);

// ❌ String concatenation
// $query = "SELECT * FROM users WHERE email = '$email'"; // Vulnerable!
```

---

## 🔐 Authentication & Authorization

### JWT Best Practices
```typescript
// ✅ Short expiration
const token = jwt.sign(
  { userId: user.id },
  process.env.JWT_SECRET!,
  { expiresIn: '15m' } // Short-lived
);

// ✅ Verify token
const decoded = jwt.verify(token, process.env.JWT_SECRET!);

// ✅ Store refresh token securely (httpOnly cookie)
res.cookie('refreshToken', refreshToken, {
  httpOnly: true,
  secure: true, // HTTPS only
  sameSite: 'strict',
  maxAge: 7 * 24 * 60 * 60 * 1000 // 7 days
});
```

### Session Management
```typescript
// ✅ Secure session configuration
app.use(session({
  secret: process.env.SESSION_SECRET!,
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: true, // HTTPS only
    httpOnly: true,
    sameSite: 'strict',
    maxAge: 3600000 // 1 hour
  }
}));
```

---

## 🛡️ Security Headers

### Next.js Configuration
```typescript
// next.config.ts
const securityHeaders = [
  {
    key: 'X-DNS-Prefetch-Control',
    value: 'on'
  },
  {
    key: 'Strict-Transport-Security',
    value: 'max-age=63072000; includeSubDomains; preload'
  },
  {
    key: 'X-Frame-Options',
    value: 'SAMEORIGIN'
  },
  {
    key: 'X-Content-Type-Options',
    value: 'nosniff'
  },
  {
    key: 'X-XSS-Protection',
    value: '1; mode=block'
  },
  {
    key: 'Referrer-Policy',
    value: 'origin-when-cross-origin'
  },
  {
    key: 'Content-Security-Policy',
    value: "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline'; style-src 'self' 'unsafe-inline';"
  }
];

export default {
  async headers() {
    return [
      {
        source: '/:path*',
        headers: securityHeaders,
      },
    ];
  },
};
```

---

## 🔑 Secrets Management

### Environment Variables
```bash
# ✅ .env.local (NEVER commit)
DATABASE_URL=postgresql://...
JWT_SECRET=long-random-string-here
API_KEY=sk_live_...

# ❌ NEVER expose secrets in client
# NEXT_PUBLIC_SECRET_KEY=... # BAD!
```

### Check Secrets Exist
```typescript
// ✅ Validate secrets at startup
if (!process.env.JWT_SECRET) {
  throw new Error('JWT_SECRET not configured');
}

if (!process.env.DATABASE_URL) {
  throw new Error('DATABASE_URL not configured');
}
```

---

## 🌐 CORS Configuration

```typescript
// ✅ Restrictive CORS
const corsOptions = {
  origin: process.env.ALLOWED_ORIGINS?.split(',') || 'http://localhost:3000',
  methods: ['GET', 'POST', 'PUT', 'DELETE'],
  credentials: true,
  maxAge: 86400,
  optionsSuccessStatus: 200
};

app.use(cors(corsOptions));
```

---

## 🚫 Input Validation

### Server-Side Validation
```typescript
import { z } from 'zod';

// ✅ Define schema
const contactSchema = z.object({
  name: z.string().min(2).max(100),
  email: z.string().email(),
  message: z.string().min(10).max(1000),
});

// ✅ Validate
export async function POST(req: Request) {
  const body = await req.json();
  
  try {
    const data = contactSchema.parse(body);
    // Process validated data
  } catch (error) {
    return NextResponse.json(
      { error: 'Invalid input' },
      { status: 400 }
    );
  }
}
```

### Sanitization
```php
// ✅ WordPress sanitization
$name = sanitize_text_field($_POST['name']);
$email = sanitize_email($_POST['email']);
$message = sanitize_textarea_field($_POST['message']);

// ✅ Validate
if (!is_email($email)) {
    wp_send_json_error('Invalid email', 400);
}
```

---

## 🔍 Security Monitoring

### Log Security Events
```typescript
// ✅ Log failed login attempts
logger.warn('Failed login attempt', {
  ip: req.ip,
  email: maskEmail(email),
  timestamp: new Date(),
  userAgent: req.headers['user-agent']
});
```

### Rate Limiting
```typescript
import rateLimit from 'express-rate-limit';

// ✅ Limit requests
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // 100 requests per windowMs
  message: 'Too many requests'
});

app.use('/api/', limiter);
```

---

## 🧪 Security Testing

### Check for Vulnerabilities
```bash
# npm audit
npm audit

# Fix vulnerabilities
npm audit fix

# Snyk scan
npx snyk test

# OWASP ZAP
docker run -t owasp/zap2docker-stable zap-baseline.py -t http://localhost:3000
```

---

## 📚 Referencias

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [OWASP Cheat Sheet Series](https://cheatsheetseries.owasp.org/)
- `/agents/SECURITY_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
