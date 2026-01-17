# 🔒 Security Guidelines - Informático Capella

## 🎯 Security Policy

Seguimos los principios de **Defense in Depth** y **OWASP Top 10** para proteger el sistema.

---

## 🛡️ Security Layers

### 1. Network Security
- ✅ HTTPS only (production)
- ✅ Firewall configurado
- ⏳ WAF (Web Application Firewall) - planned
- ⏳ DDoS protection - planned

### 2. Application Security
- ✅ Input sanitization (WordPress functions)
- ✅ Output escaping
- ✅ CORS configured
- ✅ Security headers (Next.js)
- ⏳ Rate limiting - planned
- ⏳ CSP (Content Security Policy) - planned

### 3. Data Security
- ✅ Prepared statements (PDO/wpdb)
- ✅ Password hashing (bcrypt)
- ✅ Environment variables for secrets
- ⏳ Encryption at rest - planned

### 4. Access Control
- ✅ WordPress user roles
- ⏳ JWT authentication - planned
- ⏳ API key management - planned

---

## 🔐 WordPress Security

### Hardening Checklist
```php
// wp-config.php
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);
define('WP_DEBUG', false); // Production

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Hide WordPress version
remove_action('wp_head', 'wp_generator');
```

### Plugin Security
- ✅ Only trusted plugins
- ✅ Regular updates
- ✅ Security audits
- ❌ No nulled/pirated plugins

---

## 🌐 Next.js Security

### Security Headers
```typescript
// Implemented in next.config.ts
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- Strict-Transport-Security
- Referrer-Policy
```

### Environment Variables
```bash
# ✅ Never commit secrets
# ✅ Use .env.local for development
# ✅ Use Vercel environment variables for production
# ❌ Never use NEXT_PUBLIC_ for secrets
```

---

## 🚨 Incident Response

### If Security Breach Detected:
1. **Isolate**: Take affected systems offline
2. **Assess**: Determine scope and impact
3. **Contain**: Stop the breach from spreading
4. **Eradicate**: Remove threat
5. **Recover**: Restore systems
6. **Review**: Post-mortem and improve

### Contact
- **Primary**: pellax@informaticocapella.com
- **Backup**: [GitHub Security Advisories]

---

## 🧪 Security Testing

### Regular Audits
- Weekly: npm audit
- Monthly: Dependency updates
- Quarterly: Penetration testing
- Annually: Full security audit

### Tools
```bash
# Frontend
npm audit
npx snyk test

# Backend
composer audit
docker exec informaticocapella_wp wp plugin list

# Infrastructure
docker scout cves
```

---

## 📋 Security Checklist

### Before Deploy
- [ ] All secrets in environment variables
- [ ] Security headers configured
- [ ] HTTPS enabled
- [ ] Input validation implemented
- [ ] Output escaping in place
- [ ] WordPress hardened
- [ ] Dependencies updated
- [ ] Security scan passed

---

## 📚 Referencias

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [WordPress Security](https://wordpress.org/support/article/hardening-wordpress/)
- `/agents/SECURITY_AGENT.md`
- `/skills/security/SECURITY_SKILLS.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
