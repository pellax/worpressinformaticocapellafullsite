# ⚛️ React 19 Skills - Best Practices

## 🎯 Component Fundamentals

### Functional Components
```typescript
// ✅ Typed functional component
interface Props {
  title: string;
  count?: number;
}

export function Card({ title, count = 0 }: Props) {
  return (
    <div className="card">
      <h3>{title}</h3>
      <p>Count: {count}</p>
    </div>
  );
}
```

### Component Composition
```typescript
// ✅ Compose smaller components
export function UserProfile({ user }: { user: User }) {
  return (
    <div className="profile">
      <Avatar src={user.avatar} />
      <UserInfo name={user.name} email={user.email} />
      <UserStats posts={user.posts} followers={user.followers} />
    </div>
  );
}
```

---

## 🎣 Hooks Best Practices

### useState
```typescript
// ✅ Simple state
const [count, setCount] = useState(0);

// ✅ Complex state with TypeScript
interface Form {
  email: string;
  password: string;
}

const [form, setForm] = useState<Form>({
  email: '',
  password: ''
});

// ✅ Functional updates
setCount(prev => prev + 1);
```

### useEffect
```typescript
// ✅ Effect with cleanup
useEffect(() => {
  const subscription = api.subscribe(data => {
    setData(data);
  });
  
  return () => subscription.unsubscribe(); // Cleanup
}, []); // Empty deps = run once

// ✅ Effect with dependencies
useEffect(() => {
  fetchUser(userId);
}, [userId]); // Re-run when userId changes

// ❌ Don't forget dependencies
useEffect(() => {
  console.log(count); // Missing 'count' in deps!
}, []); // ESLint will warn
```

### useCallback
```typescript
// ✅ Memoize callbacks to prevent re-renders
const handleClick = useCallback(() => {
  doSomething(value);
}, [value]); // Re-create only when value changes

// Pass to child components
<ExpensiveChild onClick={handleClick} />
```

### useMemo
```typescript
// ✅ Memoize expensive computations
const sortedItems = useMemo(() => {
  return items.sort((a, b) => a.value - b.value);
}, [items]); // Re-compute only when items change

// ❌ Don't overuse - has overhead
const simpleValue = useMemo(() => a + b, [a, b]); // Unnecessary!
```

### useRef
```typescript
// ✅ DOM reference
const inputRef = useRef<HTMLInputElement>(null);

useEffect(() => {
  inputRef.current?.focus();
}, []);

<input ref={inputRef} />

// ✅ Persistent value (doesn't trigger re-render)
const renderCount = useRef(0);
useEffect(() => {
  renderCount.current += 1;
});
```

### Custom Hooks
```typescript
// ✅ Extract reusable logic
function useLocalStorage<T>(key: string, initialValue: T) {
  const [value, setValue] = useState<T>(() => {
    const stored = localStorage.getItem(key);
    return stored ? JSON.parse(stored) : initialValue;
  });
  
  useEffect(() => {
    localStorage.setItem(key, JSON.stringify(value));
  }, [key, value]);
  
  return [value, setValue] as const;
}

// Usage
const [theme, setTheme] = useLocalStorage('theme', 'dark');
```

---

## 🎨 React 19 New Features

### useOptimistic
```typescript
'use client';

import { useOptimistic } from 'react';

function TodoList({ todos }: { todos: Todo[] }) {
  const [optimisticTodos, addOptimisticTodo] = useOptimistic(
    todos,
    (state, newTodo: Todo) => [...state, newTodo]
  );
  
  async function handleAdd(formData: FormData) {
    const newTodo = { id: Date.now(), text: formData.get('text') };
    addOptimisticTodo(newTodo); // Immediate UI update
    
    await createTodo(newTodo); // Server action
  }
  
  return (
    <form action={handleAdd}>
      {optimisticTodos.map(todo => <TodoItem key={todo.id} {...todo} />)}
    </form>
  );
}
```

### use (Resource Loading)
```typescript
// ✅ Suspend on promises
import { use } from 'react';

function UserProfile({ userPromise }: { userPromise: Promise<User> }) {
  const user = use(userPromise); // Suspend until resolved
  
  return <div>{user.name}</div>;
}

// Wrap with Suspense
<Suspense fallback={<Loading />}>
  <UserProfile userPromise={fetchUser()} />
</Suspense>
```

---

## 🔄 State Management Patterns

### Lifting State Up
```typescript
// ✅ Share state between siblings
function Parent() {
  const [filter, setFilter] = useState('all');
  
  return (
    <>
      <FilterControls filter={filter} onChange={setFilter} />
      <TodoList filter={filter} />
    </>
  );
}
```

### Context API
```typescript
// ✅ Global state without prop drilling
const ThemeContext = createContext<{
  theme: string;
  setTheme: (theme: string) => void;
}>({ theme: 'light', setTheme: () => {} });

function ThemeProvider({ children }: { children: React.ReactNode }) {
  const [theme, setTheme] = useState('light');
  
  return (
    <ThemeContext.Provider value={{ theme, setTheme }}>
      {children}
    </ThemeContext.Provider>
  );
}

// Usage
function Button() {
  const { theme } = useContext(ThemeContext);
  return <button className={theme}>{/* ... */}</button>;
}
```

---

## 🎯 Performance Optimization

### React.memo
```typescript
// ✅ Prevent unnecessary re-renders
const ExpensiveComponent = React.memo(function ExpensiveComponent({ 
  data 
}: { 
  data: Data 
}) {
  return <div>{/* Complex rendering */}</div>;
});

// With custom comparison
const MemoizedComponent = React.memo(
  Component,
  (prevProps, nextProps) => {
    return prevProps.id === nextProps.id; // Only re-render if id changes
  }
);
```

### Code Splitting
```typescript
// ✅ Lazy load components
import { lazy, Suspense } from 'react';

const HeavyChart = lazy(() => import('./HeavyChart'));

function Dashboard() {
  return (
    <Suspense fallback={<ChartSkeleton />}>
      <HeavyChart data={data} />
    </Suspense>
  );
}
```

---

## 📋 Forms Best Practices

### Controlled Components
```typescript
// ✅ Controlled input
function Form() {
  const [email, setEmail] = useState('');
  
  return (
    <form onSubmit={handleSubmit}>
      <input
        type="email"
        value={email}
        onChange={e => setEmail(e.target.value)}
      />
    </form>
  );
}
```

### Form Libraries (React Hook Form)
```typescript
import { useForm } from 'react-hook-form';

interface FormData {
  email: string;
  password: string;
}

function LoginForm() {
  const { register, handleSubmit, formState: { errors } } = useForm<FormData>();
  
  const onSubmit = (data: FormData) => {
    console.log(data);
  };
  
  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input {...register('email', { required: true })} />
      {errors.email && <span>Email required</span>}
      
      <input type="password" {...register('password', { minLength: 8 })} />
      {errors.password && <span>Min 8 characters</span>}
      
      <button type="submit">Login</button>
    </form>
  );
}
```

---

## 🚫 Common Anti-Patterns

### ❌ Mutating State Directly
```typescript
// BAD
const [items, setItems] = useState([1, 2, 3]);
items.push(4); // ❌ Direct mutation
setItems(items); // React won't detect change

// GOOD
setItems([...items, 4]); // ✅ New array
setItems(prev => [...prev, 4]); // ✅ Functional update
```

### ❌ Missing Dependencies in useEffect
```typescript
// BAD
useEffect(() => {
  fetchData(userId); // ❌ userId not in deps
}, []);

// GOOD
useEffect(() => {
  fetchData(userId); // ✅ userId in deps
}, [userId]);
```

### ❌ Overusing useEffect
```typescript
// BAD
const [firstName, setFirstName] = useState('');
const [lastName, setLastName] = useState('');
const [fullName, setFullName] = useState('');

useEffect(() => {
  setFullName(`${firstName} ${lastName}`); // ❌ Unnecessary effect
}, [firstName, lastName]);

// GOOD
const fullName = `${firstName} ${lastName}`; // ✅ Derived state
```

---

## 🎨 Component Patterns

### Compound Components
```typescript
// ✅ Related components work together
function Tabs({ children }: { children: React.ReactNode }) {
  const [activeTab, setActiveTab] = useState(0);
  
  return (
    <TabsContext.Provider value={{ activeTab, setActiveTab }}>
      {children}
    </TabsContext.Provider>
  );
}

Tabs.List = function TabsList({ children }: { children: React.ReactNode }) {
  return <div className="tabs-list">{children}</div>;
};

Tabs.Tab = function Tab({ index, children }: { index: number; children: React.ReactNode }) {
  const { activeTab, setActiveTab } = useContext(TabsContext);
  return (
    <button onClick={() => setActiveTab(index)}>
      {children}
    </button>
  );
};

// Usage
<Tabs>
  <Tabs.List>
    <Tabs.Tab index={0}>Tab 1</Tabs.Tab>
    <Tabs.Tab index={1}>Tab 2</Tabs.Tab>
  </Tabs.List>
</Tabs>
```

### Render Props
```typescript
// ✅ Share logic via render prop
function Mouse({ render }: { render: (pos: { x: number; y: number }) => React.ReactNode }) {
  const [position, setPosition] = useState({ x: 0, y: 0 });
  
  useEffect(() => {
    const handleMove = (e: MouseEvent) => {
      setPosition({ x: e.clientX, y: e.clientY });
    };
    window.addEventListener('mousemove', handleMove);
    return () => window.removeEventListener('mousemove', handleMove);
  }, []);
  
  return <>{render(position)}</>;
}

// Usage
<Mouse render={({ x, y }) => <p>Position: {x}, {y}</p>} />
```

---

## 📚 Referencias

- [React Docs](https://react.dev/)
- [React 19 Release](https://react.dev/blog/2024/04/25/react-19)
- `/agents/UI_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
