# Shramnopasak App API Documentation

This document outlines the APIs available for integrating the Shramnopasak Daily News, Comments, and Advertisements modules into the mobile application.

## 1. Daily News API

Fetch the latest news items. This API supports pagination (10 items per page) and filtering by `anchal_id`.

**Endpoint:** `GET /api/shramnopasak/daily-news`

**Query Parameters:**
- `page` (optional): The page number to fetch (e.g., `?page=2`).
- `anchal_id` (optional): Filter news by a specific Anchal ID. 

**Example Request:**
```http
GET /api/shramnopasak/daily-news?page=1&anchal_id=5
```

**Success Response:**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "title": "News Title",
            "description": "Full description of the news...",
            "anchal_id": 5,
            "photo": "path/to/photo.jpg",
            "date": "2026-07-21",
            "city_id": 2,
            "state_id": 1,
            "local_sangh_id": 10,
            "like_count": 42,
            "created_at": "2026-07-21T12:00:00.000000Z"
        }
    ],
    "first_page_url": "http://domain.com/api/shramnopasak/daily-news?page=1",
    "last_page": 5,
    "next_page_url": "http://domain.com/api/shramnopasak/daily-news?page=2",
    "total": 50
}
```

---

## 2. Like News API

Increment the like count for a specific news item.

**Endpoint:** `POST /api/shramnopasak/daily-news/{id}/like`

**Path Parameters:**
- `id`: The ID of the `daily_news` item.

**Example Request:**
```http
POST /api/shramnopasak/daily-news/1/like
```

**Success Response:**
```json
{
    "status": "success",
    "message": "Liked successfully",
    "like_count": 43
}
```

---

## 3. Comments API

Fetch and submit comments for a specific news item.

### Fetch Comments
**Endpoint:** `GET /api/shramnopasak/daily-news/{id}/comments`

**Path Parameters:**
- `id`: The ID of the `daily_news` item.

**Success Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "news_id": 1,
            "mid": 105,
            "comment": "Great initiative!",
            "created_at": "2026-07-21T12:05:00.000000Z"
        }
    ]
}
```

### Submit Comment
**Endpoint:** `POST /api/shramnopasak/daily-news/{id}/comments`

**Body (JSON or Form-Data):**
- `mid` (integer, required): Member ID of the user commenting.
- `comment` (string, required): The comment text.

**Example Request:**
```json
{
    "mid": 105,
    "comment": "Very informative news."
}
```

**Success Response:**
```json
{
    "status": "success",
    "message": "Comment added successfully"
}
```

---

## 4. Advertisement API

Fetch active advertisements to display on the app.

**Endpoint:** `GET /api/shramnopasak/advertisements`

**Success Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "photo": "path/to/ad_banner.jpg",
            "created_at": "2026-07-20T10:00:00.000000Z"
        }
    ]
}
```
