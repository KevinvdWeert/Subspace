# Subspace Design System - Quick Reference

## 🎨 Colors

### Use these CSS variables in your templates:

```css
/* Brand Colors */
var(--subspace-brand)         /* #8b5cf6 - Primary purple */
var(--subspace-brand-hover)   /* #a78bfa - Lighter purple */
var(--subspace-accent)        /* #22c55e - Green accent */
var(--subspace-accent-hover)  /* #16a34a - Darker green */

/* Surfaces */
var(--subspace-bg)            /* #0b0f19 - Page background */
var(--subspace-surface)       /* #1a1f2e - Card background */
var(--subspace-surface-hover) /* #222838 - Hover state */

/* Text */
var(--subspace-text)          /* rgba(255,255,255,0.92) - Primary */
var(--subspace-text-muted)    /* rgba(255,255,255,0.6) - Secondary */

/* Borders */
var(--subspace-border)        /* rgba(139,92,246,0.12) - Subtle */

/* Semantic */
var(--subspace-danger)        /* #ef4444 - Errors */
var(--subspace-warning)       /* #f59e0b - Warnings */
var(--subspace-info)          /* #3b82f6 - Info */
```

## 📏 Spacing

Use Bootstrap spacing utilities:

```html
<!-- Margin -->
<div class="m-0">   <!-- 0 -->
<div class="m-1">   <!-- 0.25rem / 4px -->
<div class="m-2">   <!-- 0.5rem / 8px -->
<div class="m-3">   <!-- 1rem / 16px -->
<div class="m-4">   <!-- 1.5rem / 24px -->
<div class="m-5">   <!-- 3rem / 48px -->

<!-- Padding (same values) -->
<div class="p-3">   <!-- 1rem / 16px -->

<!-- Gaps (Flexbox/Grid) -->
<div class="gap-2"> <!-- 0.5rem / 8px -->
<div class="gap-3"> <!-- 1rem / 16px -->

<!-- Directional -->
<div class="mt-3">  <!-- margin-top -->
<div class="mb-4">  <!-- margin-bottom -->
<div class="py-3">  <!-- padding vertical -->
<div class="px-4">  <!-- padding horizontal -->
```

## 🔤 Typography

### Headings
```html
<h1>Main Page Title</h1>          <!-- 2rem, weight 600 -->
<h2>Section Header</h2>            <!-- 1.5rem, weight 600 -->
<h3>Subsection</h3>                <!-- 1.25rem, weight 600 -->
<h4>Card Title</h4>                <!-- 1.125rem, weight 600 -->
<h5>Small Title</h5>               <!-- 1rem, weight 600 -->
```

### Body Text
```html
<p>Regular paragraph</p>           <!-- 0.9375rem, line-height 1.6 -->
<p class="small">Small text</p>    <!-- 0.875rem -->
<p class="text-muted">Muted</p>    <!-- 60% opacity -->
```

## 🃏 Cards

### Basic Card
```html
<div class="card mb-3 shadow-sm">
  <div class="card-body">
    <h3 class="h5">Card Title</h3>
    <p>Card content goes here</p>
  </div>
</div>
```

### Feed Post Card
```html
<div class="card mb-3 shadow-sm">
  <div class="card-body">
    <div class="d-flex gap-3">
      <!-- Vote Column -->
      <div class="text-center subspace-vote-col">
        <button class="btn btn-sm btn-primary">▲</button>
        <div class="small fw-semibold mt-1">42</div>
        <button class="btn btn-sm btn-outline-secondary">▼</button>
      </div>
      
      <!-- Content -->
      <div class="flex-grow-1">
        <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
          <a class="fw-semibold" href="#">username</a>
          <span>·</span>
          <span>2 hours ago</span>
        </div>
        <div class="mb-2">Post content here</div>
      </div>
    </div>
  </div>
</div>
```

## 🔘 Buttons

### Variants
```html
<!-- Primary Action -->
<button class="btn btn-primary">Save Changes</button>

<!-- Secondary -->
<button class="btn btn-secondary">Cancel</button>

<!-- Outlined -->
<button class="btn btn-outline-secondary">Edit</button>

<!-- Link Style -->
<button class="btn btn-link">Learn More</button>

<!-- Danger -->
<button class="btn btn-danger">Delete</button>
```

### Sizes
```html
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Default</button>
<button class="btn btn-primary btn-lg">Large</button>
```

### Button Groups
```html
<div class="d-flex gap-2">
  <button class="btn btn-primary">Save</button>
  <button class="btn btn-outline-secondary">Cancel</button>
</div>
```

## 📝 Forms

### Input Field
```html
<div class="mb-3">
  <label class="form-label" for="username">Username</label>
  <input class="form-control" id="username" type="text" placeholder="Enter username">
  <div class="form-text">Choose a unique username</div>
</div>
```

### Textarea
```html
<div class="mb-3">
  <label class="form-label" for="bio">Bio</label>
  <textarea class="form-control" id="bio" rows="3"></textarea>
</div>
```

### Select Dropdown
```html
<div class="mb-3">
  <label class="form-label" for="space">Space</label>
  <select class="form-select" id="space">
    <option>Choose...</option>
    <option value="1">Technology</option>
    <option value="2">Gaming</option>
  </select>
</div>
```

### Validation States
```html
<!-- Invalid -->
<div class="mb-3">
  <label class="form-label" for="email">Email</label>
  <input class="form-control is-invalid" id="email" type="email" value="invalid">
  <div class="invalid-feedback">Please enter a valid email</div>
</div>

<!-- Valid -->
<div class="mb-3">
  <input class="form-control is-valid" value="valid@example.com">
  <div class="valid-feedback">Looks good!</div>
</div>
```

## 🏷️ Badges

```html
<span class="badge text-bg-primary">New</span>
<span class="badge text-bg-success">Active</span>
<span class="badge text-bg-danger">Blocked</span>
<span class="badge text-bg-warning">Pending</span>
<span class="badge text-bg-info">Info</span>
<span class="badge text-bg-secondary">Admin</span>
```

## ⚠️ Alerts

```html
<!-- Success -->
<div class="alert alert-success" role="alert">
  <strong>Success!</strong> Your changes have been saved.
</div>

<!-- Danger/Error -->
<div class="alert alert-danger" role="alert">
  <strong>Error!</strong> Something went wrong.
</div>

<!-- Warning -->
<div class="alert alert-warning" role="alert">
  <strong>Warning!</strong> Please review this carefully.
</div>

<!-- Info -->
<div class="alert alert-info" role="alert">
  <strong>Info:</strong> Here's some helpful information.
</div>
```

## 📊 Tables

```html
<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>John Doe</td>
        <td><span class="badge text-bg-success">Active</span></td>
        <td>
          <button class="btn btn-sm btn-outline-secondary">Edit</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

## 🎯 Navigation

### Sidebar Nav (already in header.php)
```html
<ul class="nav nav-pills flex-column gap-1">
  <li class="nav-item">
    <a class="nav-link active" href="#">Active Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Regular Link</a>
  </li>
</ul>
```

## 📱 Responsive Utilities

### Show/Hide by Breakpoint
```html
<!-- Hide on mobile, show on desktop -->
<div class="d-none d-md-block">Desktop only</div>

<!-- Show on mobile, hide on desktop -->
<div class="d-block d-md-none">Mobile only</div>
```

### Responsive Grid
```html
<div class="row g-3">
  <div class="col-12 col-md-6 col-lg-4">
    <!-- Full width on mobile, half on tablet, third on desktop -->
  </div>
</div>
```

## 🎨 Layout Patterns

### Flexbox Container
```html
<div class="d-flex gap-3 align-items-center">
  <div>Item 1</div>
  <div>Item 2</div>
  <div class="ms-auto">Pushed right</div>
</div>
```

### Grid Layout
```html
<div class="row g-3">
  <div class="col-md-6">Column 1</div>
  <div class="col-md-6">Column 2</div>
</div>
```

### Centered Content
```html
<div class="text-center">
  <h1>Centered Title</h1>
  <p>Centered paragraph</p>
</div>
```

## 👤 Avatar Component

### Image Avatar
```html
<img class="rounded-circle border subspace-avatar-img" 
     src="avatar.jpg" 
     alt="User avatar">
```

### Fallback Avatar
```html
<div class="subspace-avatar-fallback">A</div>
```

## 🖼️ Images

### Post Image
```html
<img class="post-image img-fluid rounded border" 
     src="image.jpg" 
     alt="Post image" 
     loading="lazy">
```

### Responsive Image
```html
<img class="img-fluid" src="image.jpg" alt="Description">
```

## 💡 Common Patterns

### Profile Stats Cards
```html
<div class="row g-3">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-secondary small">Spaces</div>
        <div class="h4 mb-0">12</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-secondary small">Posts</div>
        <div class="h4 mb-0">247</div>
      </div>
    </div>
  </div>
</div>
```

### Empty State
```html
<div class="alert alert-secondary" role="alert">
  No items found. <a href="#" class="alert-link">Create one now</a>
</div>
```

### Loading State
```html
<div class="text-center py-5">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>
```

## ✅ Best Practices

### Do's ✓
- Use Bootstrap utilities first
- Use `gap-*` for flexbox spacing
- Use `text-muted` for secondary text
- Use `shadow-sm` for subtle elevation
- Use semantic HTML (`<nav>`, `<main>`, `<article>`)
- Add `aria-label` for icon-only buttons
- Use `role="alert"` for alerts
- Use `loading="lazy"` for images

### Don'ts ✗
- Don't write inline styles
- Don't use `!important` unless necessary
- Don't create custom spacing classes
- Don't forget responsive classes
- Don't skip semantic HTML
- Don't forget alt text on images
- Don't remove focus outlines

## 🔍 Testing

### Quick Checklist
- [ ] Test on mobile (< 768px)
- [ ] Test on tablet (768px-992px)
- [ ] Test on desktop (> 992px)
- [ ] Tab through all interactive elements
- [ ] Check color contrast
- [ ] Test with screen reader
- [ ] Verify hover states
- [ ] Check focus indicators

## 📚 Resources

- [Bootstrap 5.3 Documentation](https://getbootstrap.com/docs/5.3/)
- [Full Design System Guide](./visual-improvements.md)
- [Bootstrap Cheat Sheet](https://bootstrap-cheatsheet.themeselection.com/)

## 🆘 Need Help?

If you need to add something not covered by Bootstrap:

1. Check if Bootstrap has a utility for it
2. Check the design system guide
3. Use CSS variables for colors
4. Keep custom CSS minimal
5. Document any new patterns

**Example Custom CSS:**
```css
.my-custom-component {
  background: var(--subspace-surface);
  border: 1px solid var(--subspace-border);
  border-radius: var(--bs-border-radius);
  padding: var(--spacing-md);
}
```
