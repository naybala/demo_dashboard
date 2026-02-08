# Project Onboarding SOP for AI Agents

Welcome to the **Demo Dashboard** project. This document provides a high-level overview of the architecture and development patterns to help you contribute effectively from day one.

## 🏗 High-Level Architecture

The project follows a **Modified Domain-Driven Design (DDD)** with multi-layered modules.

- **`modules/Foundations/Domain`**: Core business logic, Eloquent Models, and Migrations. Models should contain `fillable` properties, relationships, and **Query Scopes** for filtering.
- **`modules/Web|Mobile|Spa`**: Environment-specific layers.
  - **`Controllers`**: Receive requests, use FormRequests for validation, call Services, and return responses via `ResponseFactory`.
  - **`Services`**: The "Brain" of the module. Handles business logic and `\DB::transaction` blocks.
  - **`Resources`**: Data transformation layer. Handles formatting (e.g., `number_format`) and ID obfuscation.
  - **`Validation`**: Contains `Store`, `Update`, and `Delete` FormRequests.

## 🛠 Core Patterns & Conventions

### 1. ID Obfuscation

- **Always** decode IDs from the request using `customDecoder($id)` in Controllers.
- **Always** encode IDs in Resources using `customEncoder($this->id)`.

### 2. Standardized Responses

- Use the `ResponseFactory` injected into Controllers for consistent redirects and JSON responses.
- Success routes usually redirect to `.index` or `.show`.

### 3. Service-First Logic

- Controllers should be thin. Move complex logic, especially anything multi-step or transactional, into the **Service** layer.
- Use `findOrFail` in Services to ensure clean error handling (404s).

### 4. Frontend Components

- The UI is built using **Blade Components** located in `resources/views/components`.
- Layouts wrap content in `<x-master-layout>`.
- Use `<x-form.input-group>` for standard inputs. It supports classes like `.comma-format` for automated thousands separators.

### 5. Number Formatting

- **Database**: Store numbers as plain decimals/integers.
- **UI (Inputs)**: Use the `.comma-format` class on inputs. The global `comma-formatter.js` handles formatting during typing and strips commas before submission.
- **UI (Display)**: Use `number_format` in Laravel Resources for table/show views.

## 🚀 Development Workflow

1.  **Domain**: Create Migration and Model in `modules/Foundations/Domain`. Add query scopes.
2.  **Request**: Create FormRequests in `modules/[Module]/Validation`.
3.  **Service**: Implement CRUD logic in `modules/[Module]/Services`.
4.  **Resource**: Define data transformation in `modules/[Module]/Resources`.
5.  **Controller**: Wire everything together in `modules/[Module]/Controllers`.
6.  **Views**: Build UI in `resources/views/admin/[module]` using Blade components.

## 📚 Key Files to Study

- `modules/Web/Common/BaseController.php`
- `resources/js/common/comma-formatter.js`
- `resources/views/components/form/input-group.blade.php`
