# Lost & Found Board — Mobile (Ionic + Capacitor, Android)

Companion Android app for the [Lost & Found Board](../lost-and-found-board)
Laravel web app. Talks to the same database through the REST API exposed by
that app — there is no separate mobile backend.

## API documentation

See [`docs/API.md`](docs/API.md) for the full endpoint reference (also
viewable inside the running app under the **API Docs** tab).

## Configuring the API base URL

The API base URL is set in `src/config.ts` and defaults to
`http://10.0.2.2/api` (the Android emulator's alias for the host machine's
`localhost`, where the Laravel app is served on port 80 via nginx).

- **Physical device**: build with `VITE_API_BASE_URL=http://<host-lan-ip>/api npm run build`
- **Browser (`ionic serve`)**: override to `http://127.0.0.1/api`

## Development

```bash
npm install
npm run dev          # browser preview via Vite
npm run build         # production web build (writes to dist/)
npx cap sync android   # copy the web build into the Android project
npx cap open android   # open in Android Studio to run on an emulator/device
```

## Stack

- Ionic React + Vite
- Capacitor (Android platform only, for this workshop)
- `@capacitor/camera` for photo capture/gallery selection when reporting an item
