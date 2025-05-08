# Form Builder with Conditional Logic (Laravel + Livewire)

This is a dynamic form builder application where users can create custom forms with conditional logic, share them via public links, and view submitted responses in their dashboard.

---

## Features

* ✅ User authentication (Laravel Breeze)
* ✅ Create and manage forms
* ✅ Add custom fields (text, email, select, radio, etc.)
* ✅ Conditional logic (show/hide fields based on answers)
* ✅ Public form submission via shareable link
* ✅ Responses stored and visible in user dashboard
* ✅ Built with reactivity using Livewire and Alpine.js

---

## Technologies Used

* **Laravel** – PHP Framework for backend and routing
* **Livewire** – Reactive components without writing JavaScript
* **Alpine.js** – Lightweight JavaScript for frontend interactivity
* **Tailwind CSS** – Utility-first CSS framework
* **MySQL** – Database to store users, forms, fields, responses
* **Laravel Breeze** – Simple auth scaffolding with Livewire support

---

## What I Learned

Through this project I learned how to:

* Use **Laravel Livewire** to build interactive components
* Create and manage **form relationships** (forms, fields, rules, responses)
* Implement **conditional logic** using Alpine.js and Livewire `@entangle`
* Use **Livewire validation** and `wire:model` for dynamic form handling
* Use **Laravel Policies** for route/controller security
* Build a dashboard UI with **minimal JavaScript**

---

## Setup Instructions (Local Development)

1. **Clone the repository:**

   ```bash
   git clone https://github.com/epcgrs/100daysofcode.git
   cd 013
   ```

2. **Install backend and frontend dependencies:**

   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Configure your ****\`\`**** file and database:**

   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```

4. **Run the development server:**

   ```bash
   php artisan serve
   ```

5. **Access the app:**

   ```
   http://localhost:8000
   ```
