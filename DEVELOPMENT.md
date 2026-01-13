# DEVELOPMENT GUIDELINES (THE LAW)

This document establishes the "Protocol and Law" for all development activities in the `webappbacninh` project. All developers must strictly adhere to these rules.

## 1. THE CONSTITUTION
The **[TECHNICAL_SPEC.md](./TECHNICAL_SPEC.md)** file is the "Constitution" of this project.
*   **Rule #1:** NO code shall be written that violates the Architecture defined in `TECHNICAL_SPEC.md`.
*   **Rule #2:** Every feature must belong to a specific **Module** (Core or Business) as defined in the Spec.
*   **Rule #3:** Data isolation is paramount. Tenant data MUST stay in Tenant DB.

## 2. WORKFLOW (QUY TRÌNH LÀM VIỆC)

### A. Development Cycle
1.  **Check Roadmap**: Consult `TECHNICAL_SPEC.md` -> "VI. LỘ TRÌNH TRIỂN KHAI" to know the current Phase.
2.  **Create Branch**:
    *   Feature: `feat/<module_name>-<short_description>` (e.g., `feat/auth-login`, `feat/ecommerce-cart`)
    *   Fix: `fix/<issue_description>` (e.g., `fix/tenant-creation-error`)
3.  **Implement**: Write code following the Coding Standards below.
4.  **Verify**: Ensure no cross-tenant data leaks.
5.  **Merge**: Pull Request review required before merging to `main`.

### B. Package Management
*   **Approved Packages**: Only packages listed in `TECHNICAL_SPEC.md` section IV are pre-approved.
*   **New Packages**: Any new package must be discussed and added to `TECHNICAL_SPEC.md` before installation.

## 3. CODING STANDARDS (QUY CHUẨN CODE)

### A. General
*   **Language**: PHP 8.2+ / Laravel 11.
*   **Style**: PSR-12.
*   **Typing**: Strong typing (declare return types and property types).

### B. Modular Structure
Code MUST be organized using `nwidart/laravel-modules`.
```
Modules/
  ├── Auth/           # Core Module
  ├── Ecommerce/      # Business Module
  └── RealEstate/     # Business Module
```
Do NOT put business logic in the default `app/` directory unless it's strictly global/landlord logic.

### C. Database & Tenancy
*   **Landlord Migrations**: `database/migrations/landlord`
*   **Tenant Migrations**: `database/migrations/tenant`
*   **Models**:
    *   Tenant Models must use `BelongsToTenant` trait (if using `stancl/tenancy` single-db mode) OR be strictly scoped to the tenant connection.
    *   **NEVER** query `tenants` table from within Tenant logic.

### D. Frontend & Theme Engine
*   **Blade**: Use for layout structure.
*   **Vue.js**: Use only for dynamic components and the **Theme Customizer**.
*   **TailwindCSS**: Use utility classes. Avoid custom CSS files unless necessary (`resources/css/custom.css`).

## 4. SETUP INSTRUCTIONS (FOR NEW DEVS)

To align with the "Law":
1.  Read `TECHNICAL_SPEC.md`.
2.  Check `composer.json` for required packages.
3.  Run `composer install` & `npm install`.
4.  Configure `.env` to support Multi-tenancy (define central domain).

---
*Verified by "The Lawmaker" (Antigravity)*
