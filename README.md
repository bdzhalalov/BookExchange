# BookExchange 

**BookExchange** is a web application that enables users to list books they own and exchange them with others. The application includes user authentication, book management, exchange requests, and notifications to streamline the book-swapping process.

---

## Features

### Core Functionalities
1. **User Management**
   - User authentication using Laravel Breeze.
   - User profiles with name, email, location, and avatar.

2. **Books Module**
   - Users can:
     - Add books with title, author, genre, condition, and cover image.
     - Edit or delete their books.
     - Search or filter books by title, author, or genre.

3. **Book Exchange Requests**
   - Users can:
     - Request a book from another user.
     - Manage incoming requests (approve/reject).
   - Request statuses: Pending, Approved, Rejected.

4. **Notifications**
   - Users are notified about request status changes.
   - Example:
     - "Your exchange request for *Book Title* has been approved."
     - "Your exchange request for *Book Title* has been rejected."

---

## Getting Started

### Prerequisites
- **Docker** with Docker Compose

### Start up project

- Go to directory with cloned project
- Use command `make build`
- Then use command `make start`

#### For subsequent launches of the application, it is enough to use the command `make run`

## Testing
- use command `make test` to run all project test

## Database Explanation

The **BookExchange** project uses a relational database model. Below is a detailed explanation of the database schema and the relationships between tables:

---

### 1. **Users Table**
The `users` table stores information about the registered users of the system.  
- **Key Fields**:
  - `id`: Primary key, unique identifier for each user.
  - `name`: Name of the user.
  - `email`: Email address of the user (used for login).
  - `location`: User's location (optional).
  - `avatar`: URL to the user's avatar image (optional).

- **Relationships**:
  - A user can have many books (`One-to-Many` relationship).
  - A user can make many book exchange requests (`One-to-Many` relationship with `requests`).
  - A user can receive notifications (`One-to-Many` relationship with `notifications`).

---

### 2. **Books Table**
The `books` table stores details of books added by users for exchange.  
- **Key Fields**:
  - `id`: Primary key, unique identifier for each book.
  - `user_id`: Foreign key referencing the owner of the book (from `users` table).
  - `title`: Title of the book.
  - `author`: Author of the book.
  - `genre`: Genre of the book.
  - `condition`: Description of the book's condition (e.g., "New", "Used").
  - `cover_image`: URL to the book's cover image.

- **Relationships**:
  - A book belongs to a user (`Many-to-One` relationship with `users`).
  - A book can have multiple exchange requests (`One-to-Many` relationship with `requests`).

---

### 3. **Book Requests Table**
The `requests` table handles the logic of requesting an exchange for books.  
- **Key Fields**:
  - `id`: Primary key, unique identifier for each request.
  - `book_id`: Foreign key referencing the requested book (from `books` table).
  - `user_id`: Foreign key referencing the user making the request (from `users` table).
  - `status`: Status of the request (values: `Pending`, `Approved`, `Rejected`).

- **Relationships**:
  - A book request is linked to a specific book (`Many-to-One` relationship with `books`).
  - A book request is made by a specific user (`Many-to-One` relationship with `users`).
  - A book request is managed by the book's owner (via `books.user_id`).

---

## TODOS
- TODOs left in the project code
- Add swagger documentation for api routes
- Start using api auth throught api-token
