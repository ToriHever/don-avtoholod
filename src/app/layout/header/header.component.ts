import { Component, signal } from '@angular/core';
import { QuestionModalService } from '../../shared/question-modal/question-modal.service';

interface NavItem {
  label: string;
  fragment: string;
}

@Component({
  selector: 'app-header',
  standalone: true,
  templateUrl: './header.component.html',
  styleUrl: './header.component.scss',
})
export class HeaderComponent {
  menuOpen = signal(false);

  constructor(readonly questionModal: QuestionModalService) {}

  readonly navItems: NavItem[] = [
    { label: 'Главная', fragment: 'top' },
    { label: 'Услуги', fragment: 'services' },
    { label: 'Автономные отопители', fragment: 'heaters' },
    { label: 'О компании', fragment: 'about' },
    { label: 'FAQ', fragment: 'faq' },
    { label: 'Контакты', fragment: 'contacts' },
  ];

  toggleMenu(): void {
    this.menuOpen.update((open) => !open);
  }

  closeMenu(): void {
    this.menuOpen.set(false);
  }
}
