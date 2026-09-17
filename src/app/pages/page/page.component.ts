import { Component, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, RouterLink } from '@angular/router';

interface PageContent {
  title: string;
  body: string;
}

@Component({
  selector: 'app-page',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './page.component.html',
  styleUrl: './page.component.scss',
})
export class PageComponent {
  readonly content = signal<PageContent | null>(null);
  readonly notFound = signal(false);

  constructor(route: ActivatedRoute, http: HttpClient) {
    route.paramMap.subscribe((params) => {
      const slug = params.get('slug');
      if (!slug) {
        this.notFound.set(true);
        return;
      }

      http.get<PageContent>(`content/pages/${slug}.json`).subscribe({
        next: (data) => this.content.set(data),
        error: () => this.notFound.set(true),
      });
    });
  }

  get paragraphs(): string[] {
    return (this.content()?.body ?? '').split(/\n{2,}/).filter((p) => p.trim().length > 0);
  }
}
