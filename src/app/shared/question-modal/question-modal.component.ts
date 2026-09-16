import { Component, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { QuestionModalService } from './question-modal.service';

const RECIPIENT_EMAIL = 'info@donavtoholod.ru';

@Component({
  selector: 'app-question-modal',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './question-modal.component.html',
  styleUrl: './question-modal.component.scss',
})
export class QuestionModalComponent {
  name = '';
  phone = '';
  question = '';
  readonly sent = signal(false);

  constructor(readonly modal: QuestionModalService) {}

  close(): void {
    this.modal.close();
  }

  onBackdropClick(event: MouseEvent): void {
    if (event.target === event.currentTarget) {
      this.close();
    }
  }

  submit(): void {
    if (!this.phone.trim() || !this.question.trim()) {
      return;
    }

    const subject = `Вопрос с сайта от ${this.name || 'клиента'}`;
    const body = `Имя: ${this.name || '—'}\nТелефон: ${this.phone}\n\nВопрос:\n${this.question}`;
    const mailtoUrl = `mailto:${RECIPIENT_EMAIL}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

    window.location.href = mailtoUrl;
    this.sent.set(true);
  }

  reset(): void {
    this.name = '';
    this.phone = '';
    this.question = '';
    this.sent.set(false);
  }
}
