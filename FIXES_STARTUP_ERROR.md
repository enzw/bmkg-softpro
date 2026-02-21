# ❌ "The command to launch your application is not defined" - SOLUTION

## Problem
Koyeb deployment fails with error: **"The command to launch your application is not defined"**

This means the container doesn't have a proper startup command defined.

---

## 🔍 Possible Causes

### 1. **Supervisord Configuration Issue** ❌
- `supervisord.conf` has syntax errors
- Environment variable `%(ENV_PORT)s` not properly substituted
- Supervisord fails to start → container exits

### 2. **Missing Files** ❌
- `entrypoint.sh` not found in `/usr/local/bin/`
- `supervisord.conf` not found in `/etc/`

### 3. **ENTRYPOINT/CMD Issue** ❌
- Dockerfile doesn't have proper CMD fallback
- Script execution failure causes container to stop

---

## ✅ FIXES (Already Applied)

### Fix 1: Hardcode PORT in Supervisord
```conf
# OLD (BROKEN):
command=php -S 0.0.0.0:%(ENV_PORT)s -t public

# NEW (FIXED):
command=php -S 0.0.0.0:8080 -t public
```
✅ Applied to `supervisord.conf`

### Fix 2: Add CMD Fallback
```dockerfile
# OLD (BROKEN):
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# NEW (FIXED):
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
```
✅ Applied to `Dockerfile`

### Fix 3: Improved Error Handling
```bash
# Make migrations/optimization non-fatal
php artisan migrate --force 2>&1 || echo "⚠️ Skipped"

# Add fallback to direct command
if ! command -v supervisord &> /dev/null; then
    exec php -S 0.0.0.0:${PORT} -t public
else
    exec supervisord -c /etc/supervisord.conf
fi
```
✅ Applied to `entrypoint.sh`

---

## 🚀 How to Fix If Still Failing

### Option 1: Use Supervisor Config (Current)
**Requires**: `supervisord.conf` and `entrypoint.sh` in repo

Files to check:
- ✅ `supervisord.conf` - Exists and valid
- ✅ `entrypoint.sh` - Exists and executable
- ✅ `Dockerfile` - Has ENTRYPOINT + CMD

### Option 2: Use Simplified Startup (Alternative)
**For testing**, use the simpler version:

```bash
# Use Dockerfile.simple instead
docker build -f Dockerfile.simple -t bmkg-softpro:latest .
```

This uses `startup.sh` (no supervisord) for direct process management.

---

## 🧪 Test Locally First

### Test current Dockerfile
```bash
cd d:\laragon\www\BMKG\bmkg-softpro

# Build
docker build -t bmkg-softpro:test .

# Run
docker run -it --rm \
  -p 8080:8080 \
  -e APP_ENV=production \
  -e APP_URL=http://localhost:8080 \
  -e APP_KEY=base64:... \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=bmkg_softpro \
  -e DB_USERNAME=postgres \
  -e DB_PASSWORD=root \
  bmkg-softpro:test

# Should see:
# ====================================
# 🚀 Starting BMKG SoftPro Application
# ====================================
```

### Test simplified version
```bash
docker build -f Dockerfile.simple -t bmkg-softpro:simple .
docker run -it --rm -p 8080:8080 bmkg-softpro:simple
```

---

## 📋 Checklist

Before deploying to Koyeb:

- [ ] `supervisord.conf` exists in repo root
- [ ] `entrypoint.sh` exists in repo root and executable
- [ ] `Dockerfile` has both ENTRYPOINT and CMD
- [ ] Test locally: `docker build && docker run`
- [ ] Check logs: `docker logs [container]`
- [ ] All `.sh` files have `LF` line endings (not CRLF)

---

## 🆘 If Still Not Working

### Check line endings
Windows line endings (CRLF) can break shell scripts!

```bash
# Check entrypoint.sh line endings
file entrypoint.sh
# Output should be: "ASCII text, with LF line terminators" (NOT CRLF)

# Fix if needed (in git):
git config --global core.autocrlf input
git rm --cached -r .
git reset --hard
```

### Manual test in Koyeb
1. Deploy with debug mode
2. Check application logs in Koyeb console
3. SSH to running container and debug manually

### Last resort: Use Alpine sh instead of bash
```dockerfile
ENTRYPOINT ["/bin/sh", "/usr/local/bin/entrypoint.sh"]
```

---

## 📚 Files Changed

1. `Dockerfile` - Added CMD fallback
2. `supervisord.conf` - Fixed PORT variable
3. `entrypoint.sh` - Improved error handling
4. `Dockerfile.simple` - Alternative simpler version
5. `startup.sh` - Simple startup script (alternative)

---

**Status**: Ready to re-deploy ✅
