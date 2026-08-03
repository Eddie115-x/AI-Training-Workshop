import { useEffect, useState } from 'react';
import { IonContent, IonHeader, IonPage, IonSpinner, IonTitle, IonToolbar } from '@ionic/react';
import { marked } from 'marked';
import apiDocsMarkdown from '../assets/api-docs.md?raw';
import './ApiDocs.css';

const ApiDocs: React.FC = () => {
  const [html, setHtml] = useState<string | null>(null);

  useEffect(() => {
    Promise.resolve(marked.parse(apiDocsMarkdown)).then((result) => setHtml(result));
  }, []);

  return (
    <IonPage>
      <IonHeader>
        <IonToolbar>
          <IonTitle>API Docs</IonTitle>
        </IonToolbar>
      </IonHeader>
      <IonContent className="ion-padding">
        {html ? (
          <div className="api-docs" dangerouslySetInnerHTML={{ __html: html }} />
        ) : (
          <IonSpinner />
        )}
      </IonContent>
    </IonPage>
  );
};

export default ApiDocs;
