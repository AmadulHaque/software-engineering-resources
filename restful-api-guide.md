# RESTful API Principles Guide

## 1. Basic HTTP Methods

| Method | Purpose | Example |
|--------|----------|---------|
| GET | Retrieve data | `GET /api/users` |
| POST | Create new data | `POST /api/users` |
| PUT | Update entire resource | `PUT /api/users/1` |
| PATCH | Partial update | `PATCH /api/users/1` |
| DELETE | Remove data | `DELETE /api/users/1` |

## 2. HTTP Status Codes

- 200: OK (Success)
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Server Error

## 3. Resource Naming

### Good Examples
```
GET /api/users                 # Get all users
GET /api/users/1              # Get specific user
GET /api/users/1/posts        # Get user's posts
GET /api/categories/5/products # Get category products
```

### Bad Examples
```
GET /api/getUsers             # Uses verb
GET /api/user/all             # Inconsistent naming
GET /api/users/1/get-posts    # Uses verb
```

## 4. Request & Response Examples

### Request
```http
GET /api/users/1
Authorization: Bearer {token}
Accept: application/json
```

### Success Response
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-27T12:00:00Z"
    }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "User not found",
    "code": 404
}
```

## 5. Query Parameters

### Pagination
```
GET /api/users?page=2&per_page=20
```

### Filtering
```
GET /api/users?status=active&role=admin
```

### Sorting
```
GET /api/users?sort=name&order=desc
```

## 6. Key Principles

1. **Stateless**
   - Each request must contain all needed information
   - No session dependency

2. **Cacheable**
   - Responses must indicate if they're cacheable
   - Use proper cache headers

3. **Client-Server**
   - Clear separation between client and server
   - Independent evolution

4. **Uniform Interface**
   - Consistent resource URLs
   - Standard HTTP methods
   - Self-descriptive messages

## 7. Security Best Practices

1. **Authentication**
   - Use tokens (JWT, OAuth)
   - HTTPS only
   - Secure headers

2. **Rate Limiting**
   ```
   X-RateLimit-Limit: 60
   X-RateLimit-Remaining: 59
   ```

3. **Input Validation**
   - Validate all inputs
   - Sanitize data
   - Use proper error messages

## 8. Versioning

### URL Versioning
```
/api/v1/users
/api/v2/users
```

### Header Versioning
```
Accept: application/vnd.company.api-v1+json
```

## 9. Documentation Example

```php
/**
 * @api {get} /users/:id Get User
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiParam {Number} id User's unique ID
 *
 * @apiSuccess {String} name User's name
 * @apiSuccess {String} email User's email
 */
```

## 10. Implementation Checklist

- [ ] Use proper HTTP methods
- [ ] Return correct status codes
- [ ] Implement authentication
- [ ] Add rate limiting
- [ ] Include error handling
- [ ] Add API documentation
- [ ] Implement data validation
- [ ] Use JSON for data exchange
- [ ] Add API versioning
- [ ] Include pagination for lists
