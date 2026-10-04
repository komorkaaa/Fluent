<section class="page-head pb-4">
    <div class="container">
        <a href="/admin/courses" class="breadcrumb-link"><?= icon('arrow-left') ?> Все курсы</a>
        <h1>Новый курс</h1>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <?php partial('course-form', [
                    'course' => $course,
                    'errors' => $errors,
                    'action' => '/admin/courses/create',
                    'submitLabel' => 'Создать курс',
                    'languages' => $languages,
                    'levels' => $levels,
                    'formats' => $formats,
                ]); ?>
            </div>
        </div>
    </div>
</section>
