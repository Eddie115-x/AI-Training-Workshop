import { API_BASE_URL } from '../config';
import type { Item, NewItem, PaginatedItems } from '../types';

export interface ItemFilters {
  type?: 'lost' | 'found';
  status?: 'claimed';
  page?: number;
}

export class ApiError extends Error {
  constructor(message: string, public errors?: Record<string, string[]>) {
    super(message);
  }
}

async function handle<T>(res: Response): Promise<T> {
  const body = await res.json().catch(() => null);

  if (!res.ok) {
    throw new ApiError(body?.message ?? `Request failed with status ${res.status}`, body?.errors);
  }

  return body as T;
}

export function fetchItems(filters: ItemFilters = {}): Promise<PaginatedItems> {
  const params = new URLSearchParams();
  if (filters.type) params.set('type', filters.type);
  if (filters.status) params.set('status', filters.status);
  if (filters.page) params.set('page', String(filters.page));

  return fetch(`${API_BASE_URL}/items?${params.toString()}`, {
    headers: { Accept: 'application/json' },
  }).then((res) => handle<PaginatedItems>(res));
}

export function fetchItem(id: number): Promise<{ data: Item }> {
  return fetch(`${API_BASE_URL}/items/${id}`, {
    headers: { Accept: 'application/json' },
  }).then((res) => handle<{ data: Item }>(res));
}

export function createItem(item: NewItem): Promise<{ data: Item }> {
  const form = new FormData();
  form.append('title', item.title);
  form.append('description', item.description);
  form.append('type', item.type);
  form.append('location', item.location);
  form.append('contact', item.contact);
  if (item.photo) {
    form.append('photo', item.photo.blob, item.photo.filename);
  }

  return fetch(`${API_BASE_URL}/items`, {
    method: 'POST',
    headers: { Accept: 'application/json' },
    body: form,
  }).then((res) => handle<{ data: Item }>(res));
}

export function markItemClaimed(id: number): Promise<{ data: Item }> {
  return fetch(`${API_BASE_URL}/items/${id}/claim`, {
    method: 'PATCH',
    headers: { Accept: 'application/json' },
  }).then((res) => handle<{ data: Item }>(res));
}
