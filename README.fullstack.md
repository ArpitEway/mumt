# mumt React + Node Setup

This repository now contains a new full-stack app beside the legacy CodeIgniter files.

- `frontend/`: React + Vite portal UI
- `backend/`: Node + Express API connected to local MySQL
- default database name: `mumtdb`

## Setup

1. Copy `backend/.env.example` to `backend/.env` and set your MySQL user/password.
2. Copy `frontend/.env.example` to `frontend/.env` if your API URL is different.
3. Install dependencies:

```bash
npm run install:all
```

4. Start both apps:

```bash
npm run dev
```

Frontend in this workspace: `http://localhost:5174`  
Backend in this workspace: `http://localhost:5001/api`

The local `.env` files use backend port `5001` and frontend port `5174`, because port `5000` was already busy on this machine during setup.

## Current Coverage

The Node API includes:

- admin login via `admin_master.user_name` and legacy MD5 password
- center login via `center.center_code`, `password`, and `status = 'Y'`
- student login via `student.enrollment_no` and DOB
- teacher and exam-center login hooks
- dashboard totals from existing MySQL tables
- role-aware menu loading from legacy menu tables
- generic listing/searching and admin CRUD for approved tables
- student profile and exam paper API endpoints

The React frontend includes:

- role-based login screen
- dashboard summaries
- legacy menu display
- table browser with search, pagination, add, edit, and delete for admin users
- student profile and exam-paper view

The old PHP project has hundreds of specialized views and controller actions. This new stack gives you a working React/Node foundation connected to `mumtdb`, ready for migrating each specialized workflow screen by screen.
