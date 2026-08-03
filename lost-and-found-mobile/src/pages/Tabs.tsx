import { Redirect, Route } from 'react-router-dom';
import { IonIcon, IonLabel, IonRouterOutlet, IonTabBar, IonTabButton, IonTabs } from '@ionic/react';
import { addCircleOutline, documentTextOutline, listOutline } from 'ionicons/icons';
import ItemList from './ItemList';
import ItemDetail from './ItemDetail';
import ItemCreate from './ItemCreate';
import ApiDocs from './ApiDocs';

const Tabs: React.FC = () => (
  <IonTabs>
    <IonRouterOutlet>
      <Route exact path="/items" component={ItemList} />
      <Route exact path="/items/new" component={ItemCreate} />
      <Route exact path="/items/:id(\d+)" component={ItemDetail} />
      <Route exact path="/docs" component={ApiDocs} />
      <Route exact path="/">
        <Redirect to="/items" />
      </Route>
    </IonRouterOutlet>

    <IonTabBar slot="bottom">
      <IonTabButton tab="items" href="/items">
        <IonIcon icon={listOutline} />
        <IonLabel>Board</IonLabel>
      </IonTabButton>
      <IonTabButton tab="new" href="/items/new">
        <IonIcon icon={addCircleOutline} />
        <IonLabel>Report</IonLabel>
      </IonTabButton>
      <IonTabButton tab="docs" href="/docs">
        <IonIcon icon={documentTextOutline} />
        <IonLabel>API Docs</IonLabel>
      </IonTabButton>
    </IonTabBar>
  </IonTabs>
);

export default Tabs;
