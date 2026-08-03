import { useEffect, useState } from 'react';
import { RouteComponentProps } from 'react-router-dom';
import {
  IonBackButton,
  IonBadge,
  IonButton,
  IonButtons,
  IonContent,
  IonHeader,
  IonIcon,
  IonImg,
  IonItem,
  IonLabel,
  IonList,
  IonLoading,
  IonPage,
  IonText,
  IonTitle,
  IonToolbar,
  useIonViewWillEnter,
} from '@ionic/react';
import { checkmarkCircleOutline, imageOutline, locationOutline, mailOutline } from 'ionicons/icons';
import { fetchItem, markItemClaimed } from '../services/api';
import type { Item } from '../types';

interface Props extends RouteComponentProps<{ id: string }> {}

const ItemDetail: React.FC<Props> = ({ match }) => {
  const id = Number(match.params.id);
  const [item, setItem] = useState<Item | null>(null);
  const [loading, setLoading] = useState(true);
  const [claiming, setClaiming] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const load = () => {
    setLoading(true);
    fetchItem(id)
      .then((res) => setItem(res.data))
      .catch((e) => setError(e instanceof Error ? e.message : 'Failed to load item.'))
      .finally(() => setLoading(false));
  };

  useEffect(load, [id]);
  useIonViewWillEnter(load);

  const handleClaim = async () => {
    setClaiming(true);
    try {
      const res = await markItemClaimed(id);
      setItem(res.data);
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Failed to update item.');
    } finally {
      setClaiming(false);
    }
  };

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonButtons slot="start">
            <IonBackButton defaultHref="/items" />
          </IonButtons>
          <IonTitle>Item Details</IonTitle>
        </IonToolbar>
      </IonHeader>
      <IonContent className="ion-padding">
        <IonLoading isOpen={loading} message="Loading..." />

        {error && <IonText color="danger">{error}</IonText>}

        {item && (
          <>
            {item.photo_url ? (
              <IonImg src={item.photo_url} alt={item.title} />
            ) : (
              <div
                style={{
                  height: 200,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  background: 'var(--ion-color-light)',
                }}
              >
                <IonIcon icon={imageOutline} style={{ fontSize: '3rem', color: 'var(--ion-color-medium)' }} />
              </div>
            )}

            <div className="ion-padding-top" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <h1>{item.title}</h1>
              <IonBadge color={item.is_claimed ? 'medium' : item.type === 'lost' ? 'danger' : 'success'}>
                {item.is_claimed ? 'Claimed' : item.type === 'lost' ? 'Lost' : 'Found'}
              </IonBadge>
            </div>

            <p>{item.description}</p>

            <IonList>
              <IonItem>
                <IonIcon icon={locationOutline} slot="start" />
                <IonLabel>
                  <p>Location</p>
                  <h3>{item.location}</h3>
                </IonLabel>
              </IonItem>
              <IonItem>
                <IonIcon icon={mailOutline} slot="start" />
                <IonLabel>
                  <p>Contact</p>
                  <h3>{item.contact}</h3>
                </IonLabel>
              </IonItem>
            </IonList>

            {!item.is_claimed && (
              <IonButton expand="block" className="ion-margin-top" onClick={handleClaim} disabled={claiming}>
                <IonIcon icon={checkmarkCircleOutline} slot="start" />
                Mark as Claimed
              </IonButton>
            )}
          </>
        )}
      </IonContent>
    </IonPage>
  );
};

export default ItemDetail;
