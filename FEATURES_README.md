# CuroHelp - Client & Worker Management Features

This document describes the newly implemented features for client and worker management.

## Features Implemented

### 1. Database Models and Migrations

**Client Model** (`app/Models/Client.php`)
- Fields: user_id, name, phone, email, address
- Relationship: belongs to User

**Worker Model** (`app/Models/Worker.php`)
- Fields: user_id, name, phone, email, address, invoice_path
- Relationship: belongs to User

**Migrations:**
- `2026_01_06_075728_create_clients_table.php`
- `2026_01_06_075734_create_workers_table.php`

### 2. Filament Admin Resources

#### Client Resource
**Location:** `app/Filament/Resources/Clients/ClientResource.php`

**Form Fields:**
- Name (required)
- Phone
- Email (unique, required)
- Address (textarea)

**Table Columns:**
- Name (searchable, sortable)
- Email (searchable, sortable)
- Phone (searchable)
- Address (limited preview with tooltip)
- Created At (toggleable)

**Access:** Admin only

#### Worker Resource
**Location:** `app/Filament/Resources/Workers/WorkerResource.php`

**Form Fields:**
- Name (required)
- Phone
- Email (unique)
- Address (textarea)
- Invoice File Upload (PDF only, max 5MB)
  - Stored in: `storage/app/public/invoices/workers`
  - Downloadable and openable in admin panel

**Table Columns:**
- Name (searchable, sortable)
- Email (searchable, sortable)
- Phone (searchable)
- Address (limited preview with tooltip)
- Invoice Status (badge: Uploaded/No invoice)
- Created At (toggleable)

**Access:** Admin only

**Email Notification:**
When a worker uploads an invoice, an automatic email is sent to `hello@curohelp.com` with:
- Worker details
- Invoice filename
- Link to admin panel

### 3. Frontend Dashboards (Bootstrap Only)

#### Client Dashboard
**Base Route:** `/client/dashboard`
**Middleware:** `auth`, `role:client`

**Pages:**
1. **Dashboard Overview** (`/client/dashboard`)
   - Statistics cards (Active Projects, Pending Invoices, Unread Messages)
   - Recent activity section
   - Quick actions

2. **Messages** (`/client/messages`)
   - Placeholder for messaging system

3. **Invoices** (`/client/invoices`)
   - Placeholder for invoice listing

4. **Profile Settings** (`/client/profile`)
   - Account information form
   - Password change form
   - Profile picture upload

#### Worker Dashboard
**Base Route:** `/worker/dashboard`
**Middleware:** `auth`, `role:worker`

**Pages:**
1. **Dashboard Overview** (`/worker/dashboard`)
   - Statistics cards (Completed Jobs, Uploaded Invoices, Unread Messages)
   - Recent jobs section
   - Quick actions

2. **Messages** (`/worker/messages`)
   - Placeholder for messaging system

3. **Upload Invoice** (`/worker/upload-invoice`)
   - Invoice upload form (PDF only)
   - Invoice details fields (number, date, amount, notes)
   - Upload guidelines
   - Recent uploads list

4. **Profile Settings** (`/worker/profile`)
   - Account information form
   - Password change form
   - Profile picture upload

### 4. Layout Design

**Layout File:** `resources/views/layouts/dashboard.blade.php`

**Features:**
- Responsive Bootstrap 5 design
- Fixed top navbar with user info and logout
- Left sidebar with menu items
- Collapsible sidebar on mobile
- Minimalistic design

**Colors:**
- Background: White (#FFFFFF)
- Text: Black (#000000)
- Accent: #DBB88E (buttons, active links, headings)

**Mobile Responsive:**
- Sidebar collapses on screens < 768px
- Hamburger menu toggle
- Overlay for mobile sidebar

### 5. Authentication & Authorization

**Roles:**
- `client` - Access to client dashboard
- `worker` - Access to worker dashboard
- `admin` - Access to Filament admin panel

**Middleware:**
- `EnsureRole` middleware handles role checking
- Routes protected with `role:client` and `role:worker`

**Dashboard Redirect:**
`DashboardRedirectController` automatically redirects authenticated users to appropriate dashboard based on role.

### 6. Example Users (Seeded)

Run the seeder to create example users:
```bash
php artisan db:seed --class=ClientWorkerSeeder
```

**Client User:**
- Email: `client@example.com`
- Password: `password`
- Role: client

**Worker User:**
- Email: `worker@example.com`
- Password: `password`
- Role: worker

## Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Example Users
```bash
php artisan db:seed --class=ClientWorkerSeeder
```

### 3. Create Storage Link (if not already done)
```bash
php artisan storage:link
```

### 4. Configure Mail (for invoice notifications)
Update `.env` file with your mail settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@curohelp.com
MAIL_FROM_NAME="CuroHelp"
```

## File Structure

```
app/
├── Filament/
│   └── Resources/
│       ├── Clients/
│       │   ├── ClientResource.php
│       │   ├── Schemas/ClientForm.php
│       │   └── Tables/ClientsTable.php
│       └── Workers/
│           ├── WorkerResource.php
│           ├── Schemas/WorkerForm.php
│           └── Tables/WorkersTable.php
├── Http/
│   ├── Controllers/
│   │   ├── ClientDashboardController.php
│   │   └── WorkerDashboardController.php
│   └── Middleware/
│       └── EnsureRole.php
├── Mail/
│   └── WorkerInvoiceUploaded.php
├── Models/
│   ├── Client.php
│   └── Worker.php
└── Observers/
    └── WorkerObserver.php

database/
├── migrations/
│   ├── 2026_01_06_075728_create_clients_table.php
│   └── 2026_01_06_075734_create_workers_table.php
└── seeders/
    └── ClientWorkerSeeder.php

resources/
└── views/
    ├── dashboards/
    │   ├── client/
    │   │   ├── index.blade.php
    │   │   ├── messages.blade.php
    │   │   ├── invoices.blade.php
    │   │   └── profile.blade.php
    │   └── worker/
    │       ├── index.blade.php
    │       ├── messages.blade.php
    │       ├── upload-invoice.blade.php
    │       └── profile.blade.php
    ├── emails/
    │   └── worker-invoice-uploaded.blade.php
    └── layouts/
        └── dashboard.blade.php

routes/
└── web.php
```

## Access URLs

### Admin Panel
- URL: `/admin`
- Access: Admin users only
- Manage clients and workers via Filament resources

### Client Dashboard
- URL: `/client/dashboard`
- Login: `client@example.com` / `password`
- Access: Client role required

### Worker Dashboard
- URL: `/worker/dashboard`
- Login: `worker@example.com` / `password`
- Access: Worker role required

## Next Steps / Future Enhancements

1. **Multiple Addresses Support**
   - Implement address relationship table
   - Allow clients/workers to manage multiple addresses

2. **Invoice Management**
   - Complete invoice upload functionality for workers
   - Invoice listing and viewing for clients
   - PDF generation and download

3. **Messaging System**
   - Real-time chat between clients, workers, and admin
   - Message notifications

4. **Profile Management**
   - Implement profile update functionality
   - Password change functionality
   - Profile picture upload

5. **Dashboard Statistics**
   - Connect real data to statistics cards
   - Recent activity implementation

## Notes

- All placeholder pages are ready for future implementation
- Code is well-commented and organized
- Bootstrap 5 is used exclusively (no React or extra UI libraries)
- Design is minimalistic and clean
- Mobile responsive out of the box
- Email notifications are set up for invoice uploads
