<?php
/** @var array $course */
$url = '/courses/' . (int) $course['id'];
?>
<article class="course-card">
    <a href="<?= $url ?>" tabindex="-1" aria-hidden="true">
        <img
            src="<?= e(course_image($course)) ?>"
            class="cover"
            alt=""
            loading="lazy"
            width="800"
            height="500"
        >
    </a>

    <div class="card-body">
        <div class="chips">
            <span class="chip"><?= e($course['language']) ?></span>
            <span class="chip chip-yellow"><?= e($course['level']) ?></span>
            <span class="chip chip-mint"><?= e($course['format']) ?></span>
        </div>

        <h3>
            <a href="<?= $url ?>" class="text-reset text-decoration-none">
                <?= e($course['name']) ?>
            </a>
        </h3>

        <p class="excerpt"><?= e($course['description']) ?></p>

        <div class="d-flex align-items-center justify-content-between gap-3 mt-auto pt-2">
            <span class="price-tag"><?= e(price($course['price'])) ?></span>

            <a href="<?= $url ?>" class="btn btn-outline-primary btn-sm">
                Подробнее
            </a>
        </div>
    </div>
</article>
