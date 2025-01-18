<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Konsultasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_properties()
    {
        $review = new Review();

        $this->assertEquals([
            'konsultasi_id',
            'rating',
            'pesan',
        ], $review->getFillable());
    }

    /** @test */
    public function it_belongs_to_konsultasi()
    {
        $konsultasi = Konsultasi::factory()->create();
        $review = Review::factory()->create(['konsultasi_id' => $konsultasi->konsultasi_id]);

        $this->assertTrue($review->konsultasi->is($konsultasi));
    }
}
