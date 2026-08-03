import { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import {
  IonBadge,
  IonContent,
  IonFab,
  IonFabButton,
  IonHeader,
  IonIcon,
  IonInfiniteScroll,
  IonInfiniteScrollContent,
  IonItem,
  IonLabel,
  IonList,
  IonPage,
  IonRefresher,
  IonRefresherContent,
  IonSegment,
  IonSegmentButton,
  IonSpinner,
  IonText,
  IonThumbnail,
  IonTitle,
  IonToolbar,
  useIonViewWillEnter,
} from '@ionic/react';
import { add, imageOutline } from 'ionicons/icons';
import { fetchItems } from '../services/api';
import type { Item, ItemType } from '../types';

type TypeFilter = 'all' | ItemType;
type StatusFilter = 'all' | 'claimed';

function badgeColor(item: Item): string {
  if (item.is_claimed) return 'medium';
  return item.type === 'lost' ? 'danger' : 'success';
}

function badgeLabel(item: Item): string {
  if (item.is_claimed) return 'Claimed';
  return item.type === 'lost' ? 'Lost' : 'Found';
}

const ItemList: React.FC = () => {
  const history = useHistory();
  const [items, setItems] = useState<Item[]>([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [typeFilter, setTypeFilter] = useState<TypeFilter>('all');
  const [statusFilter, setStatusFilter] = useState<StatusFilter>('all');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const load = async (targetPage: number, replace: boolean) => {
    try {
      const result = await fetchItems({
        type: typeFilter === 'all' ? undefined : typeFilter,
        status: statusFilter === 'all' ? undefined : statusFilter,
        page: targetPage,
      });
      setItems((prev) => (replace ? result.data : [...prev, ...result.data]));
      setPage(result.meta.current_page);
      setLastPage(result.meta.last_page);
      setError(null);
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Failed to load items.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    setLoading(true);
    load(1, true);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [typeFilter, statusFilter]);

  useIonViewWillEnter(() => {
    load(1, true);
  });

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonTitle>Lost &amp; Found Board</IonTitle>
        </IonToolbar>
        <IonToolbar>
          <IonSegment
            value={typeFilter}
            onIonChange={(e) => setTypeFilter(e.detail.value as TypeFilter)}
          >
            <IonSegmentButton value="all">
              <IonLabel>All</IonLabel>
            </IonSegmentButton>
            <IonSegmentButton value="lost">
              <IonLabel>Lost</IonLabel>
            </IonSegmentButton>
            <IonSegmentButton value="found">
              <IonLabel>Found</IonLabel>
            </IonSegmentButton>
          </IonSegment>
        </IonToolbar>
        <IonToolbar>
          <IonSegment
            value={statusFilter}
            onIonChange={(e) => setStatusFilter(e.detail.value as StatusFilter)}
          >
            <IonSegmentButton value="all">
              <IonLabel>All statuses</IonLabel>
            </IonSegmentButton>
            <IonSegmentButton value="claimed">
              <IonLabel>Claimed only</IonLabel>
            </IonSegmentButton>
          </IonSegment>
        </IonToolbar>
      </IonHeader>

      <IonContent>
        <IonRefresher slot="fixed" onIonRefresh={(e) => load(1, true).then(() => e.detail.complete())}>
          <IonRefresherContent />
        </IonRefresher>

        {loading && (
          <div className="ion-text-center ion-padding">
            <IonSpinner />
          </div>
        )}

        {error && (
          <div className="ion-padding">
            <IonText color="danger">{error}</IonText>
          </div>
        )}

        {!loading && !error && items.length === 0 && (
          <div className="ion-text-center ion-padding">
            <IonText color="medium">No items match these filters yet.</IonText>
          </div>
        )}

        <IonList>
          {items.map((item) => (
            <IonItem key={item.id} button onClick={() => history.push(`/items/${item.id}`)}>
              <IonThumbnail slot="start">
                {item.photo_url ? (
                  <img src={item.photo_url} alt={item.title} />
                ) : (
                  <IonIcon icon={imageOutline} style={{ fontSize: '2rem', color: 'var(--ion-color-medium)' }} />
                )}
              </IonThumbnail>
              <IonLabel>
                <h2>{item.title}</h2>
                <p>{item.location}</p>
              </IonLabel>
              <IonBadge color={badgeColor(item)} slot="end">
                {badgeLabel(item)}
              </IonBadge>
            </IonItem>
          ))}
        </IonList>

        <IonInfiniteScroll
          disabled={page >= lastPage}
          onIonInfinite={async (e) => {
            await load(page + 1, false);
            (e.target as HTMLIonInfiniteScrollElement).complete();
          }}
        >
          <IonInfiniteScrollContent loadingText="Loading more items..." />
        </IonInfiniteScroll>

        <IonFab vertical="bottom" horizontal="end" slot="fixed">
          <IonFabButton onClick={() => history.push('/items/new')}>
            <IonIcon icon={add} />
          </IonFabButton>
        </IonFab>
      </IonContent>
    </IonPage>
  );
};

export default ItemList;
