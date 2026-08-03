// The Laravel API is served by the same nginx setup as the web board.
// - Android emulator: 10.0.2.2 is the emulator's alias for the host machine.
// - Physical device: replace with the host machine's LAN/public IP.
// - Browser (`ionic serve`) on this machine: 127.0.0.1 works directly.
// Override at build time with VITE_API_BASE_URL if your setup differs.
export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL ?? 'http://10.0.2.2/api';
