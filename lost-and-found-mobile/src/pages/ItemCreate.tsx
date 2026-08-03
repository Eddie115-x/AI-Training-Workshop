import { useState } from 'react';
import { useHistory } from 'react-router-dom';
import {
  IonBackButton,
  IonButton,
  IonButtons,
  IonContent,
  IonHeader,
  IonIcon,
  IonImg,
  IonInput,
  IonItem,
  IonLabel,
  IonLoading,
  IonPage,
  IonSegment,
  IonSegmentButton,
  IonText,
  IonTextarea,
  IonTitle,
  IonToolbar,
  useIonToast,
} from '@ionic/react';
import { camera, imagesOutline } from 'ionicons/icons';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { ApiError, createItem } from '../services/api';
import type { ItemType } from '../types';

const ItemCreate: React.FC = () => {
  const history = useHistory();
  const [present] = useIonToast();

  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [type, setType] = useState<ItemType>('lost');
  const [location, setLocation] = useState('');
  const [contact, setContact] = useState('');
  const [photoPreview, setPhotoPreview] = useState<string | null>(null);
  const [photoBlob, setPhotoBlob] = useState<Blob | null>(null);

  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  const pickPhoto = async (source: CameraSource) => {
    try {
      const photo = await Camera.getPhoto({
        resultType: CameraResultType.Uri,
        source,
        quality: 80,
      });

      if (!photo.webPath) return;
      const blob = await fetch(photo.webPath).then((r) => r.blob());
      setPhotoBlob(blob);
      setPhotoPreview(photo.webPath);
    } catch {
      // User cancelled the picker — nothing to do.
    }
  };

  const handleSubmit = async () => {
    setSubmitting(true);
    setErrors({});
    try {
      const res = await createItem({
        title,
        description,
        type,
        location,
        contact,
        photo: photoBlob ? { blob: photoBlob, filename: 'photo.jpg' } : null,
      });
      present({ message: 'Item reported successfully.', duration: 2000, color: 'success' });
      history.replace(`/items/${res.data.id}`);
    } catch (e) {
      if (e instanceof ApiError && e.errors) {
        setErrors(e.errors);
      } else {
        present({
          message: e instanceof Error ? e.message : 'Failed to submit item.',
          duration: 2500,
          color: 'danger',
        });
      }
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonButtons slot="start">
            <IonBackButton defaultHref="/items" />
          </IonButtons>
          <IonTitle>Report an Item</IonTitle>
        </IonToolbar>
      </IonHeader>
      <IonContent className="ion-padding">
        <IonLoading isOpen={submitting} message="Submitting..." />

        <IonSegment value={type} onIonChange={(e) => setType(e.detail.value as ItemType)}>
          <IonSegmentButton value="lost">
            <IonLabel>Lost</IonLabel>
          </IonSegmentButton>
          <IonSegmentButton value="found">
            <IonLabel>Found</IonLabel>
          </IonSegmentButton>
        </IonSegment>

        <IonItem className="ion-margin-top">
          <IonLabel position="stacked">Title</IonLabel>
          <IonInput value={title} onIonInput={(e) => setTitle(e.detail.value ?? '')} placeholder="e.g. Black Leather Wallet" />
        </IonItem>
        {errors.title && <IonText color="danger"><p className="ion-padding-start">{errors.title[0]}</p></IonText>}

        <IonItem>
          <IonLabel position="stacked">Description</IonLabel>
          <IonTextarea
            value={description}
            onIonInput={(e) => setDescription(e.detail.value ?? '')}
            autoGrow
            placeholder="Color, brand, distinguishing features..."
          />
        </IonItem>
        {errors.description && <IonText color="danger"><p className="ion-padding-start">{errors.description[0]}</p></IonText>}

        <IonItem>
          <IonLabel position="stacked">Location</IonLabel>
          <IonInput value={location} onIonInput={(e) => setLocation(e.detail.value ?? '')} placeholder="Where was it lost / found?" />
        </IonItem>
        {errors.location && <IonText color="danger"><p className="ion-padding-start">{errors.location[0]}</p></IonText>}

        <IonItem>
          <IonLabel position="stacked">Contact info</IonLabel>
          <IonInput value={contact} onIonInput={(e) => setContact(e.detail.value ?? '')} placeholder="Email or phone number" />
        </IonItem>
        {errors.contact && <IonText color="danger"><p className="ion-padding-start">{errors.contact[0]}</p></IonText>}

        <div className="ion-margin-top">
          {photoPreview && <IonImg src={photoPreview} style={{ maxHeight: 200, objectFit: 'cover' }} />}
          <div style={{ display: 'flex', gap: 8 }} className="ion-margin-top">
            <IonButton fill="outline" onClick={() => pickPhoto(CameraSource.Camera)}>
              <IonIcon icon={camera} slot="start" />
              Camera
            </IonButton>
            <IonButton fill="outline" onClick={() => pickPhoto(CameraSource.Photos)}>
              <IonIcon icon={imagesOutline} slot="start" />
              Gallery
            </IonButton>
          </div>
        </div>

        <IonButton expand="block" className="ion-margin-top" onClick={handleSubmit} disabled={submitting}>
          Submit
        </IonButton>
      </IonContent>
    </IonPage>
  );
};

export default ItemCreate;
