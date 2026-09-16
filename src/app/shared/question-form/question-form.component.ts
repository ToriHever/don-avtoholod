import { Component, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';

const RECIPIENT_EMAIL = 'info@donavtoholod.ru';

@Component({
  selector: 'app-question-form',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './question-form.component.html',
  styleUrl: './question-form.component.scss',
})
export class QuestionFormComponent {
  name = '';
  email = '';
  phone = '';
  question = '';
  readonly sent = signal(false);

  submit(): void {
    if (!this.phone.trim() || !this.question.trim()) {
      return;
    }

    const subject = `Вопрос с сайта от ${this.name || 'клиента'}`;
    const body = [
      `Имя: ${this.name || '—'}`,
      `Телефон: ${this.phone}`,
      `Email: ${this.email || '—'}`,
      '',
      'Вопрос:',
      this.question,
    ].join('\n');
    const mailtoUrl = `mailto:${RECIPIENT_EMAIL}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

    window.location.href = mailtoUrl;
    this.sent.set(true);
  }

  reset(): void {
    this.name = '';
    this.email = '';
    this.phone = '';
    this.question = '';
    this.sent.set(false);
  }
}
