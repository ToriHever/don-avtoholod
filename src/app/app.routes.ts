import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { PageComponent } from './pages/page/page.component';

export const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'p/:slug', component: PageComponent },
];
