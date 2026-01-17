# ⚡ Next.js 14 Skills - Best Practices

## 🎯 App Router Fundamentals

### Server Components (Default)
```typescript
// ✅ Server Component - fetch data directly
export default async function Page() {
  const data = await fetch('https://api.example.com/data', {
    next: { revalidate: 3600 } // ISR: revalidate every hour
  });
  
  return <div>{/* render */}</div>;
}
```

### Client Components (When Needed)
```typescript
'use client'; // ✅ Only when you need interactivity

import { useState } from 'react';

export function InteractiveComponent() {
  const [count, setCount] = useState(0);
  return <button onClick={() => setCount(count + 1)}>{count}</button>;
}
```

**Use Client Components for**:
- `useState`, `useEffect`, event listeners
- Browser APIs (localStorage, etc.)
- Third-party libraries with browser dependencies

---

## 📁 File-Based Routing

### Route Structure
```
app/
├── layout.tsx              # Root layout
├── page.tsx                # Home: /
├── about/
│   └── page.tsx            # /about
├── blog/
│   ├── page.tsx            # /blog
│   └── [slug]/
│       └── page.tsx        # /blog/[slug] (dynamic)
└── api/
    └── contact/
        └── route.ts        # API route
```

### Dynamic Routes
```typescript
// app/blog/[slug]/page.tsx
export default function BlogPost({ 
  params 
}: { 
  params: { slug: string } 
}) {
  return <h1>Post: {params.slug}</h1>;
}

// Generate static params at build time
export async function generateStaticParams() {
  const posts = await getPosts();
  return posts.map(post => ({ slug: post.slug }));
}
```

---

## 🔄 Data Fetching Patterns

### SSG (Static Site Generation)
```typescript
// ✅ Static at build time
export default async function Page() {
  const data = await fetch('https://api.example.com/static', {
    cache: 'force-cache' // Default behavior
  });
  return <div>{/* ... */}</div>;
}
```

### ISR (Incremental Static Regeneration)
```typescript
// ✅ Static + revalidate periodically
export default async function Page() {
  const data = await fetch('https://api.example.com/posts', {
    next: { revalidate: 3600 } // Revalidate every hour
  });
  return <div>{/* ... */}</div>;
}
```

### SSR (Server-Side Rendering)
```typescript
// ✅ Fresh data on every request
export default async function Page() {
  const data = await fetch('https://api.example.com/dynamic', {
    cache: 'no-store' // Don't cache
  });
  return <div>{/* ... */}</div>;
}
```

### Parallel Data Fetching
```typescript
// ✅ Fetch in parallel
export default async function Page() {
  const [posts, users] = await Promise.all([
    getPosts(),
    getUsers()
  ]);
  
  return <div>{/* ... */}</div>;
}
```

---

## 🎨 Metadata API

### Static Metadata
```typescript
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Informático Capella - Consultoría Tecnológica',
  description: 'Experto en arquitectura de software y DevOps',
  openGraph: {
    title: 'Informático Capella',
    description: 'Consultoría tecnológica especializada',
    images: ['/og-image.jpg'],
  },
};

export default function Page() {
  return <div>{/* ... */}</div>;
}
```

### Dynamic Metadata
```typescript
export async function generateMetadata({ 
  params 
}: { 
  params: { slug: string } 
}): Promise<Metadata> {
  const post = await getPost(params.slug);
  
  return {
    title: post.title,
    description: post.excerpt,
    openGraph: {
      images: [post.featuredImage],
    },
  };
}
```

---

## 🖼️ Image Optimization

### Next/Image Component
```typescript
import Image from 'next/image';

// ✅ Local images
<Image
  src="/profile.jpg"
  alt="Profile"
  width={500}
  height={500}
  priority // For above-the-fold images
/>

// ✅ External images (configure in next.config.ts)
<Image
  src="https://api.example.com/image.jpg"
  alt="External"
  width={800}
  height={600}
  quality={90}
/>
```

### Configuration
```typescript
// next.config.ts
export default {
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'api.example.com',
        pathname: '/images/**',
      },
    ],
  },
};
```

---

## 🚀 Performance Optimization

### Code Splitting with Dynamic Imports
```typescript
import dynamic from 'next/dynamic';

// ✅ Lazy load component
const HeavyComponent = dynamic(() => import('./HeavyComponent'), {
  loading: () => <div>Loading...</div>,
  ssr: false, // Don't render on server
});

export default function Page() {
  return <HeavyComponent />;
}
```

### Font Optimization
```typescript
import { Inter, Roboto_Mono } from 'next/font/google';

const inter = Inter({
  subsets: ['latin'],
  display: 'swap',
  variable: '--font-inter',
});

const robotoMono = Roboto_Mono({
  subsets: ['latin'],
  display: 'swap',
  variable: '--font-roboto-mono',
});

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="es" className={`${inter.variable} ${robotoMono.variable}`}>
      <body>{children}</body>
    </html>
  );
}
```

---

## 🛣️ API Routes

### GET Handler
```typescript
// app/api/posts/route.ts
import { NextResponse } from 'next/server';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const page = searchParams.get('page') || '1';
  
  const posts = await getPosts(parseInt(page));
  
  return NextResponse.json(posts);
}
```

### POST Handler
```typescript
export async function POST(request: Request) {
  const body = await request.json();
  
  // Validate
  if (!body.email) {
    return NextResponse.json(
      { error: 'Email required' },
      { status: 400 }
    );
  }
  
  // Process
  await createLead(body);
  
  return NextResponse.json({ success: true });
}
```

---

## 🔐 Environment Variables

### Usage
```typescript
// ✅ Server-side (secure)
const apiKey = process.env.API_SECRET_KEY;

// ✅ Client-side (public)
const publicUrl = process.env.NEXT_PUBLIC_API_URL;
```

### Configuration
```bash
# .env.local (NEVER commit)
DATABASE_URL=postgresql://...
API_SECRET_KEY=secret123

# Public variables (exposed to browser)
NEXT_PUBLIC_API_URL=https://api.example.com
```

---

## 🎯 Server Actions (Experimental)

```typescript
'use server';

export async function createPost(formData: FormData) {
  const title = formData.get('title');
  const content = formData.get('content');
  
  // Server-side logic
  await db.posts.create({ title, content });
  
  revalidatePath('/blog');
  redirect('/blog');
}

// Usage in Client Component
'use client';
export function CreatePostForm() {
  return (
    <form action={createPost}>
      <input name="title" />
      <textarea name="content" />
      <button type="submit">Create</button>
    </form>
  );
}
```

---

## 📦 Middleware

```typescript
// middleware.ts
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export function middleware(request: NextRequest) {
  // Authentication check
  const token = request.cookies.get('token');
  
  if (!token && request.nextUrl.pathname.startsWith('/dashboard')) {
    return NextResponse.redirect(new URL('/login', request.url));
  }
  
  // Add custom header
  const response = NextResponse.next();
  response.headers.set('x-custom-header', 'value');
  
  return response;
}

export const config = {
  matcher: ['/dashboard/:path*', '/api/:path*'],
};
```

---

## 🧪 Testing Best Practices

### Component Testing
```typescript
import { render, screen } from '@testing-library/react';
import Page from './page';

describe('Home Page', () => {
  it('renders hero section', () => {
    render(<Page />);
    expect(screen.getByRole('heading')).toBeInTheDocument();
  });
});
```

---

## 🚫 Common Pitfalls to Avoid

### ❌ Don't: Use Client Components unnecessarily
```typescript
// BAD: Everything as client component
'use client';
export default function Page() {
  return <StaticContent />; // No interactivity needed!
}
```

### ✅ Do: Keep Server Components as default
```typescript
// GOOD: Server Component by default
export default function Page() {
  return (
    <div>
      <StaticContent />
      <InteractiveButton /> {/* Only this is 'use client' */}
    </div>
  );
}
```

### ❌ Don't: Import Server-only code in Client Components
```typescript
'use client';
import db from '@/lib/db'; // ❌ Database in client!
```

### ✅ Do: Use Server Actions or API Routes
```typescript
'use client';
export function Form() {
  async function handleSubmit() {
    // ✅ Call API route
    await fetch('/api/submit', { method: 'POST' });
  }
}
```

---

## 📚 Referencias

- [Next.js Docs](https://nextjs.org/docs)
- [App Router Migration Guide](https://nextjs.org/docs/app/building-your-application/upgrading/app-router-migration)
- `/agents/UI_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
