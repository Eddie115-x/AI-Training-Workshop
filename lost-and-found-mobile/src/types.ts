export type ItemType = 'lost' | 'found';

export interface Item {
  id: number;
  title: string;
  description: string;
  type: ItemType;
  location: string;
  contact: string;
  photo_url: string | null;
  is_claimed: boolean;
  created_at: string;
  updated_at: string;
}

export interface PaginatedItems {
  data: Item[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export interface NewItem {
  title: string;
  description: string;
  type: ItemType;
  location: string;
  contact: string;
  photo?: { blob: Blob; filename: string } | null;
}
