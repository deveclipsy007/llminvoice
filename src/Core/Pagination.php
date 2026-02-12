<?php

declare(strict_types=1);

namespace App\Core;

class Pagination
{
    public readonly int $totalItems;
    public readonly int $perPage;
    public readonly int $currentPage;
    public readonly int $totalPages;
    public readonly int $offset;

    public function __construct(int $totalItems, int $perPage = 15, int $currentPage = 1)
    {
        $this->totalItems = max(0, $totalItems);
        $this->perPage = max(1, $perPage);
        $this->totalPages = max(1, (int) ceil($this->totalItems / $this->perPage));
        $this->currentPage = max(1, min($currentPage, $this->totalPages));
        $this->offset = ($this->currentPage - 1) * $this->perPage;
    }

    public function hasPrevious(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function previousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function nextPage(): int
    {
        return min($this->totalPages, $this->currentPage + 1);
    }

    public function pages(int $around = 2): array
    {
        $pages = [];
        $start = max(1, $this->currentPage - $around);
        $end = min($this->totalPages, $this->currentPage + $around);

        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = '...';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $pages[] = '...';
            }
            $pages[] = $this->totalPages;
        }

        return $pages;
    }

    public function info(): string
    {
        $from = $this->offset + 1;
        $to = min($this->offset + $this->perPage, $this->totalItems);

        return "{$from}-{$to} " . __('messages.of') . " {$this->totalItems}";
    }

    public static function calculate(int $total, int $perPage, int $page): self
    {
        return new self($total, $perPage, $page);
    }
}
