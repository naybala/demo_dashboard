# Demo Dashboard

A modular **Laravel 12** admin dashboard with a scalable architecture, feature scaffolding, and clean separation between Domain, Application, and Web layers.

---

## ✨ Features

- **Modular Architecture**: Clean separation into `modules/` structure.
- **CRUD Scaffolding**: Custom Artisan commands for rapid feature development.
- **Inertia.js + Vue 3**: Modern, reactive frontend with seamless Laravel integration.
- **Service Layer**: Decoupled business logic for maintainability.
- **Advanced Permissions**: Comprehensive Role/Permission management via Spatie.
- **School Management**: Features like Students, Classes, and **Announcements** with built-in filters (Duration, Search).
- **ID Obfuscation**: Secure, encoded IDs in public URLs.
- **Deletion Guards**: Proactive checks to prevent orphaned data.

---

## 🏗️ Architecture Overview

Each module in `modules/` is organized into three distinct layers:

```text
modules/
  FeatureName/
    Domain/        → Eloquent Models, Repositories, core logic
    Application/   → Business Logic / Services (Cross-module or complex logic)
    Web/           → Controllers, Requests, Resources, Views (Vue 3)
    Routes.php     → Module-specific routes
```

### Core Principles

- **Lean Controllers**: Handle HTTP requests only; delegate logic to services.
- **Fat Services**: Centralize business logic and database transactions.
- **Validated Input**: Always use FormRequest classes.
- **Obfuscated IDs**: Protect internal database IDs in public routes.

---

## 🚀 Getting Started

### 1. Installation

```bash
git clone https://github.com/naybala/demo_dashboard.git
cd demo_dashboard
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

\_Configure `DB\__`settings in`.env` before proceeding.\*

### 3. Database & Dev

```bash
php artisan migrate --seed
php artisan serve
npm run dev
```

---

## 🛠️ Standard Operating Procedures (SOP)

### 1. Creating a New Feature

Always use the custom scaffolding commands to ensure architectural consistency:

```bash
# Generate full CRUD module (Domain, Service, Web, Views)
php artisan make:coreFeature --all FeatureName
```

### 2. ID Handling

Public URLs must use obfuscated IDs.

- **In Tests/Routes**: Use `customEncoder($id)`.
- **In Controllers/Services**: Use `customDecoder($id)`.

### 3. Testing Standards

All new features must include Feature Tests in `tests/Feature`.

- **Component Assertions**: Use `assertInertia` to verify component names and props.
- **Deletion Safety**: Implement `canDelete()` or `hasDependencies()` checks in models/services and verify in tests using `assertDatabaseHas` upon failed deletion.
- **ID Verification**: Ensure tests correctly pass encoded IDs to route helpers.

### 4. Deletion Guards (Example)

Before deleting a record, check for dependencies in the Service or Model:

```php
if ($item->hasDependencies()) {
    throw new WarningException('item.in_use');
}
```

---

## 🧰 Custom Artisan Commands

| Command                             | Description                                             |
| :---------------------------------- | :------------------------------------------------------ |
| `make:coreFeature --all {Name}`     | Generates full module structure including Vue 3 views.    |
| `make:coreFeature --logic {Name}`   | Generates Domain and Application layers only.           |
| `make:coreFeature --view {Name}`    | Generates Vue 3 views only.                               |
| `add-fields-to-view --model={Name}` | Appends new fields to existing generated views.         |

---

## 🧪 Testing

Run the full test suite with:

```bash
php artisan test
```

---

## 📦 Tech Stack

- **PHP**: ^8.0.2
- **Laravel**: v12.0
- **Frontend**: Inertia.js (v2.3) + Vue 3 (v3.5)
- **Styling**: TailwindCSS + Preline UI
- **Database**: MySQL
- **Permissions**: Spatie Laravel Permission (v6.9)
- **Utilities**: ApexCharts, CropperJS

---

## 📐 Coding Guidelines

- **Validation**: Use FormRequest classes; never validate inside Services.
- **Resources**: Use API Resources for consistent data formatting between Backend and Frontend.
- **Transactions**: Complex write operations in Services must use `DB::transaction()`.

---

## 👤 Author

**Nay Ba La**

- [GitHub](https://github.com/naybala)
- [Portfolio](https://naybala.netlify.app)
- [Mini CRUD Generator (Composer Package)](https://packagist.org/packages/davion190510/mini-crud-generator)

---

## ⭐ Support

If you find this project useful, please give it a **star** on GitHub!
