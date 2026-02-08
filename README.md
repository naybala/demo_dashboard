# Demo Dashboard

A modular **Laravel-based admin dashboard** with scalable architecture, feature scaffolding, and clean separation between Domain, Application, and Web layers.

---

## ✨ Features

- Modular architecture (`modules/` structure)
- CRUD scaffolding via custom Artisan commands
- Service layer separation for business logic
- Form Request validation
- API Resources for response formatting
- TailwindCSS + modern UI components
- Ready for scaling into multi-feature admin systems

---

## 🏗️ Architecture Overview

Each feature/module is organized into clear layers:

```
modules/
  FeatureName/
    Domain/        → Models, repositories, core logic
    Application/   → Services / Actions (business rules)
    Web/           → Controllers, Requests, Resources, Views
    Routes.php     → Module routes
```

### Principles

- **Skinny Controllers**
- **Fat Services / Actions**
- **Validated input only**
- **No HTTP logic inside services**
- **Reusable modular features**

---

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/naybala/demo_dashboard.git
cd demo_dashboard
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database inside `.env`.

---

### 4. Run migrations & seeders

```bash
php artisan migrate
php artisan db:seed
```

---

### 5. Start the development server

```bash
php artisan serve
npm run dev
```

Visit:

```
http://127.0.0.1:8000
```

---

## 🧰 Custom Artisan Generators

This project includes **feature scaffolding commands**.

### Generate full feature

```bash
php artisan make:coreFeature--all
```

### Generate logic only

```bash
php artisan make:coreFeature--logic
```

# Demo Dashboard

A modular **Laravel-based admin dashboard** with scalable architecture, feature scaffolding, and clean separation between Domain, Application, and Web layers.

---

## ✨ Features

- Modular architecture (`modules/` structure)
- CRUD scaffolding via custom Artisan commands
- Service layer separation for business logic
- Form Request validation
- API Resources for response formatting
- TailwindCSS + modern UI components
- Ready for scaling into multi-feature admin systems

---

## 🏗️ Architecture Overview

Each feature/module is organized into clear layers:

```
modules/
  FeatureName/
    Domain/        → Models, repositories, core logic
    Application/   → Services / Actions (business rules)
    Web/           → Controllers, Requests, Resources, Views
    Routes.php     → Module routes
```

### Principles

- **Skinny Controllers**
- **Fat Services / Actions**
- **Validated input only**
- **No HTTP logic inside services**
- **Reusable modular features**

---

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/naybala/demo_dashboard.git
cd demo_dashboard
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database inside `.env`.

---

### 4. Run migrations & seeders

```bash
php artisan migrate
php artisan db:seed
```

---

### 5. Start the development server

```bash
php artisan serve
npm run dev
```

Visit:

```
http://127.0.0.1:8000
```

---

## 🧰 Custom Artisan Generators

This project includes **feature scaffolding commands**.

### Generate full feature

```bash
php artisan make:coreFeature--all FeatureName
```

### Generate logic only

```bash
php artisan make:coreFeature--logic FeatureName
```

### Generate views only

```bash
php artisan make:coreFeature--view FeatureName
```

### Add fields to generated views

```bash
php artisan add-fields-to-view FeatureName
```

These commands speed up CRUD module creation and enforce consistent structure.

---

## 🧪 Testing

Run tests with:

```bash
php artisan test
```

> Future improvements will expand **unit and feature test coverage**.

---

## 📦 Tech Stack

- **Laravel** (v10+ compatible)
- **MySQL**
- **TailwindCSS**
- **Flowbite UI**
- **Spatie Permission**
- **Debugbar / IDE Helper**
- **Docker support**

---

## 📐 Coding Guidelines

### Controllers

- Handle **HTTP only**
- Call **services/actions**
- Return **views or JSON**

### Services / Actions

- Contain **business logic**
- Use **DB transactions**
- Receive **validated data only**
- Must **not return views**

### Validation

- Use **FormRequest classes**
- Never validate inside services

### Resources

- Format **API / view response data**
- Avoid heavy logic

---

## 🔐 Security Notes

- Always use `$request->validated()`
- Never trust raw request input
- Use authorization policies where needed

---

## 🛣️ Roadmap

- [ ] Full automated test coverage
- [ ] CI/CD with GitHub Actions
- [ ] Repository pattern integration
- [ ] Multi-tenant support
- [ ] API authentication layer

---

## 🤝 Contributing

Pull requests are welcome.

Suggested flow:

1. Fork the repository
2. Create a feature branch
3. Follow coding guidelines
4. Add tests if applicable
5. Submit PR

---

## 📄 License

Open-source under the **MIT License**.

---

## 👤 Author

**Nay Ba La**

- GitHub: [https://github.com/naybala](https://github.com/naybala)
- Portfolio: [https://naybala.netlify.app](https://naybala.netlify.app)

---

## ⭐ Support

If you find this project useful, please consider giving it a **star** on GitHub.

### Add fields to generated views

```bash
php artisan add-fields-to-view --model={FeatureName}
```

These commands speed up CRUD module creation and enforce consistent structure.

---

## 🧪 Testing

Run tests with:

```bash
php artisan test
```

> Future improvements will expand **unit and feature test coverage**.

---

## 📦 Tech Stack

- **Laravel** (v10+ compatible)
- **MySQL**
- **TailwindCSS**
- **Flowbite UI**
- **Spatie Permission**
- **Debugbar / IDE Helper**
- **Docker support**

---

## 📐 Coding Guidelines

### Controllers

- Handle **HTTP only**
- Call **services/actions**
- Return **views or JSON**

### Services / Actions

- Contain **business logic**
- Use **DB transactions**
- Receive **validated data only**
- Must **not return views**

### Validation

- Use **FormRequest classes**
- Never validate inside services

### Resources

- Format **API / view response data**
- Avoid heavy logic

---

## 🔐 Security Notes

- Always use `$request->validated()`
- Never trust raw request input
- Use authorization policies where needed

---

## 🛣️ Roadmap

- [ ] Full automated test coverage
- [ ] CI/CD with GitHub Actions
- [ ] Repository pattern integration
- [ ] Multi-tenant support
- [ ] API authentication layer

---

## 🤝 Contributing

Pull requests are welcome.

Suggested flow:

1. Fork the repository
2. Create a feature branch
3. Follow coding guidelines
4. Add tests if applicable
5. Submit PR

---

## 📄 License

Open-source under the **MIT License**.

---

## 👤 Author

**Nay Ba La**

- GitHub: [https://github.com/naybala](https://github.com/naybala)
- Portfolio: [https://naybala.netlify.app](https://naybala.netlify.app)
- my composer pkg : [https://packagist.org/packages/davion190510/mini-crud-generator](https://packagist.org/packages/davion190510/mini-crud-generator)

---

## ⭐ Support

If you find this project useful, please consider giving it a **star** on GitHub.
