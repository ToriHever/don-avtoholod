import { Component } from '@angular/core';
import { QuestionModalService } from '../../shared/question-modal/question-modal.service';
import { QuestionFormComponent } from '../../shared/question-form/question-form.component';
import homeContent from '../../../assets/content/home.json';

interface Stat {
  value: string;
  label: string;
}

interface Feature {
  icon: string;
  title: string;
  text: string;
}

interface ServiceGroup {
  title: string;
  items: string[];
}

interface RefillStep {
  title: string;
  text: string;
}

interface CompanyFact {
  title: string;
  text: string;
}

interface FaqItem {
  question: string;
  answer: string;
}

interface HomeContent {
  hero: {
    badge: string;
    title: string;
    lead: string;
    primaryButton: string;
    secondaryButton: string;
    stats: Stat[];
    ownBadge: string;
    mediaBadgeTitle: string;
    mediaBadgeText: string;
  };
  features: Feature[];
  services: {
    eyebrow: string;
    title: string;
    lead: string;
    groups: ServiceGroup[];
  };
  coolingIssues: {
    eyebrow: string;
    title: string;
    lead: string;
    button: string;
    issues: string[];
  };
  refillSteps: {
    eyebrow: string;
    title: string;
    lead: string;
    steps: RefillStep[];
  };
  heaters: {
    eyebrow: string;
    title: string;
    lead: string;
    brands: string[];
    details: RefillStep[];
  };
  about: {
    eyebrow: string;
    title: string;
    lead: string;
    badgeTitle: string;
    badgeText: string;
    ownBadge: string;
    stats: Stat[];
    paragraph1: string;
    paragraph2Before: string;
  };
  facts: {
    eyebrow: string;
    title: string;
    items: CompanyFact[];
  };
  priceNote: string;
  contacts: {
    eyebrow: string;
    title: string;
    lead: string;
    address: string;
    mapUrl: string;
    hours: string;
    email: string;
    phone1: string;
    phone2: string;
    questionButton: string;
  };
  faq: {
    eyebrow: string;
    title: string;
    formTitle: string;
    items: FaqItem[];
  };
}

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [QuestionFormComponent],
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss',
})
export class HomeComponent {
  constructor(readonly questionModal: QuestionModalService) {}

  readonly content: HomeContent = homeContent as HomeContent;
}
