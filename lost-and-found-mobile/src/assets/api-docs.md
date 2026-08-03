# Lost & Found Board — API Documentation

A JSON REST API over the same `items` table used by the web app, for the
Android/Ionic mobile client (or any other client) to consume. There is no
separate database — the API and the web UI read and write the exact same
SQLite database, so changes made from the mobile app show up on the web
board immediately and vice versa.

## Base URL

```
http://<server-host>/api
```

- **In the browser/emulator on this machine**: `http://127.0.0.1/api`
- **From the Android emulator**: use `http://10.0.2.2/api` (the emulator's
  alias for the host machine's `localhost`)
- **From a physical Android device**: use the host machine's LAN/public IP,
  e.g. `http://172.26.3.184/api`

The API is served by the same nginx + php-fpm setup that serves the web app
on port 80 (there is no `/api` prefix needed beyond what's shown above —
`routes/api.php` is automatically prefixed with `/api` by Laravel).

There is no authentication in v1 — every endpoint is open, matching the "no
auth needed for v1" scope of the web app.

## Content type

- Requests with a photo must be sent as `multipart/form-data`.
- Requests without a photo may be sent as `multipart/form-data` or
  `application/x-www-form-urlencoded`.
- All responses are `application/json`.
- Send `Accept: application/json` on every request so Laravel returns JSON
  error bodies (validation errors, 404s) instead of HTML error pages.

## Data model

| Field         | Type    | Notes                                              |
|---------------|---------|-----------------------------------------------------|
| `id`          | integer | |
| `title`       | string  | |
| `description` | string  | |
| `type`        | string  | `"lost"` or `"found"` |
| `location`    | string  | |
| `contact`     | string  | free text — email or phone number |
| `photo_url`   | string\|null | absolute URL to the uploaded photo, or `null` |
| `is_claimed`  | boolean | |
| `created_at`  | string  | ISO 8601 |
| `updated_at`  | string  | ISO 8601 |

---

## `GET /api/items`

List items. Supports the same filters and pagination as the web board.

**Query parameters** (all optional):

| Param    | Values           | Effect                                |
|----------|------------------|----------------------------------------|
| `type`   | `lost`, `found`  | Only return items of this type        |
| `status` | `claimed`        | Only return items marked as claimed   |
| `page`   | integer          | Page number (9 items per page)        |

**Example**

```bash
curl "http://127.0.0.1/api/items?type=lost&page=2"
```

**Response `200`** — a Laravel paginated resource collection:

```json
{
  "data": [
    {
      "id": 3,
      "title": "Ray-Ban Aviator Sunglasses",
      "description": "Classic gold-frame aviator sunglasses in a black zip case.",
      "type": "lost",
      "location": "Computer Lab 3",
      "contact": "(555) 728-1656",
      "photo_url": null,
      "is_claimed": false,
      "created_at": "2026-08-03T02:51:41+00:00",
      "updated_at": "2026-08-03T02:51:41+00:00"
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 9,
    "total": 15
  }
}
```

---

## `POST /api/items`

Report a new lost or found item.

**Body** (`multipart/form-data`):

| Field         | Required | Rules                                  |
|---------------|----------|------------------------------------------|
| `title`       | yes      | string, max 255                          |
| `description` | yes      | string                                    |
| `type`        | yes      | `lost` or `found`                        |
| `location`    | yes      | string, max 255                          |
| `contact`     | yes      | string, max 255                          |
| `photo`       | no       | image file (jpg/png/gif/webp/etc), max 4MB |

**Example**

```bash
curl -X POST http://127.0.0.1/api/items \
  -H "Accept: application/json" \
  -F "title=Black Leather Wallet" \
  -F "description=Found near the main entrance, contains a few cards." \
  -F "type=found" \
  -F "location=Front Office Reception" \
  -F "contact=jane.doe@example.com" \
  -F "photo=@/path/to/photo.jpg"
```

**Response `201`**:

```json
{
  "data": {
    "id": 19,
    "title": "Black Leather Wallet",
    "description": "Found near the main entrance, contains a few cards.",
    "type": "found",
    "location": "Front Office Reception",
    "contact": "jane.doe@example.com",
    "photo_url": "http://127.0.0.1/storage/items/abc123.jpg",
    "is_claimed": false,
    "created_at": "2026-08-03T03:06:56+00:00",
    "updated_at": "2026-08-03T03:06:56+00:00"
  }
}
```

**Response `422`** (validation failure) — same shape as any Laravel
validation error:

```json
{
  "message": "The title field is required. (and 4 more errors)",
  "errors": {
    "title": ["The title field is required."],
    "description": ["The description field is required."],
    "type": ["The type field is required."],
    "location": ["The location field is required."],
    "contact": ["The contact field is required."]
  }
}
```

---

## `GET /api/items/{id}`

Fetch a single item.

```bash
curl http://127.0.0.1/api/items/3
```

**Response `200`**: `{ "data": { ...same shape as above... } }`

**Response `404`** if the item doesn't exist:

```json
{ "message": "No query results for model [App\\Models\\Item] 9999" }
```

---

## `PATCH /api/items/{id}/claim`

Mark an item as claimed. No request body needed.

```bash
curl -X PATCH http://127.0.0.1/api/items/3/claim -H "Accept: application/json"
```

**Response `200`**: the updated item, with `"is_claimed": true`.

---

## Notes for the mobile client

- CORS is open (`allowed_origins: ['*']`) for all `/api/*` routes, so
  requests from the Capacitor WebView are allowed.
- The server is plain HTTP (no TLS) in this workshop environment. Capacitor's
  Android WebView blocks `http://` requests as mixed content when the app's
  local origin is `https://` (the default). Set `androidScheme: "http"` in
  `capacitor.config.ts` so the app's own origin is also `http://`, avoiding
  the mixed-content block. This has no effect on a production deployment
  where you'd switch back to `https` end-to-end.
- `photo_url` is always an absolute URL — use it directly as an `<img src>`
  or React Native/Ionic `<img>` source without needing to prepend a host.
