import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.workshop.lostandfound',
  appName: 'Lost & Found Board',
  webDir: 'dist',
  // The Laravel API in this environment is plain HTTP with no TLS. Capacitor's
  // default local scheme is https, and Android WebView blocks fetches to
  // http:// as mixed content from an https:// origin regardless of the
  // usesCleartextTraffic manifest flag (that flag only governs raw sockets,
  // not WebView mixed-content policy). Using http for the local scheme too
  // keeps origin and API on the same scheme, so requests aren't blocked.
  server: {
    androidScheme: 'http',
  },
};

export default config;
