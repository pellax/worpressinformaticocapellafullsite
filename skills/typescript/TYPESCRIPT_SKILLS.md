# 📘 TypeScript Skills - Best Practices

## 🎯 Type Basics

### Primitive Types
```typescript
// ✅ Basic types
const name: string = 'John';
const age: number = 30;
const active: boolean = true;
const data: null = null;
const notDefined: undefined = undefined;

// ✅ Arrays
const numbers: number[] = [1, 2, 3];
const names: Array<string> = ['John', 'Jane'];

// ✅ Tuple
const coord: [number, number] = [10, 20];
```

### Interfaces vs Types
```typescript
// ✅ Interface (prefer for objects)
interface User {
  id: number;
  name: string;
  email?: string; // Optional
  readonly createdAt: Date; // Read-only
}

// ✅ Type alias
type ID = string | number;
type Status = 'pending' | 'approved' | 'rejected'; // Union

// ✅ Extending interfaces
interface Admin extends User {
  role: 'admin';
  permissions: string[];
}

// ✅ Intersection types
type Employee = User & {
  employeeId: string;
  department: string;
};
```

---

## 🔧 Advanced Types

### Generics
```typescript
// ✅ Generic function
function identity<T>(arg: T): T {
  return arg;
}

const num = identity<number>(42);
const str = identity('hello');

// ✅ Generic interface
interface ApiResponse<T> {
  data: T;
  status: number;
  message: string;
}

const userResponse: ApiResponse<User> = {
  data: { id: 1, name: 'John', createdAt: new Date() },
  status: 200,
  message: 'Success'
};

// ✅ Generic constraints
function getProperty<T, K extends keyof T>(obj: T, key: K): T[K] {
  return obj[key];
}

const user = { id: 1, name: 'John' };
const name = getProperty(user, 'name'); // OK
// const invalid = getProperty(user, 'invalid'); // Error
```

### Utility Types
```typescript
// ✅ Partial (all properties optional)
type PartialUser = Partial<User>;

// ✅ Required (all properties required)
type RequiredUser = Required<User>;

// ✅ Pick (select properties)
type UserPreview = Pick<User, 'id' | 'name'>;

// ✅ Omit (exclude properties)
type UserWithoutEmail = Omit<User, 'email'>;

// ✅ Record
type UserRoles = Record<string, 'admin' | 'user'>;

// ✅ ReturnType
function getUser() {
  return { id: 1, name: 'John' };
}
type UserReturn = ReturnType<typeof getUser>;
```

---

## 🎨 React + TypeScript

### Component Props
```typescript
// ✅ Functional component props
interface ButtonProps {
  label: string;
  onClick: () => void;
  variant?: 'primary' | 'secondary';
  disabled?: boolean;
}

export function Button({ label, onClick, variant = 'primary', disabled }: ButtonProps) {
  return <button onClick={onClick} disabled={disabled}>{label}</button>;
}

// ✅ With children
interface CardProps {
  title: string;
  children: React.ReactNode;
}

export function Card({ title, children }: CardProps) {
  return (
    <div>
      <h3>{title}</h3>
      {children}
    </div>
  );
}
```

### Hooks with TypeScript
```typescript
// ✅ useState
const [count, setCount] = useState<number>(0);
const [user, setUser] = useState<User | null>(null);

// ✅ useRef
const inputRef = useRef<HTMLInputElement>(null);

// ✅ useReducer
type Action = 
  | { type: 'increment' }
  | { type: 'decrement' }
  | { type: 'reset'; payload: number };

function reducer(state: number, action: Action): number {
  switch (action.type) {
    case 'increment': return state + 1;
    case 'decrement': return state - 1;
    case 'reset': return action.payload;
  }
}

const [state, dispatch] = useReducer(reducer, 0);
```

---

## 🔐 Type Guards

```typescript
// ✅ typeof guard
function padLeft(value: string, padding: string | number) {
  if (typeof padding === 'number') {
    return ' '.repeat(padding) + value;
  }
  return padding + value;
}

// ✅ instanceof guard
class Dog {
  bark() { console.log('Woof!'); }
}

function makeSound(animal: Dog | Cat) {
  if (animal instanceof Dog) {
    animal.bark(); // TypeScript knows it's Dog
  }
}

// ✅ Custom type guard
interface Bird {
  fly(): void;
}

interface Fish {
  swim(): void;
}

function isFish(pet: Bird | Fish): pet is Fish {
  return (pet as Fish).swim !== undefined;
}

function move(pet: Bird | Fish) {
  if (isFish(pet)) {
    pet.swim();
  } else {
    pet.fly();
  }
}
```

---

## 🚫 Common Pitfalls

### ❌ Don't use `any`
```typescript
// BAD
function process(data: any) { // ❌ Defeats purpose of TypeScript
  return data.someProp;
}

// GOOD
function process(data: unknown) { // ✅ Force type checking
  if (typeof data === 'object' && data !== null && 'someProp' in data) {
    return (data as { someProp: string }).someProp;
  }
}
```

### ✅ Use strict mode
```json
// tsconfig.json
{
  "compilerOptions": {
    "strict": true,
    "noImplicitAny": true,
    "strictNullChecks": true,
    "strictFunctionTypes": true
  }
}
```

---

## 📚 Referencias

- [TypeScript Handbook](https://www.typescriptlang.org/docs/handbook/intro.html)
- [React TypeScript Cheatsheet](https://react-typescript-cheatsheet.netlify.app/)
- `/agents/UI_AGENT.md`

---

**Versión**: 1.0  
**Última actualización**: 2026-01-17
