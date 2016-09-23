<?php $set = [];?>

<?php if (isset($courses) && count($courses) !== 0):?>
    {% for course in courses %}

        <?php if (!in_array($course->section, $set)):?>

            <?php if (!empty($set)):?>
                </div>
            <?php endif;?>

            <div class="list-group">
            <span class="list-group-item active">
                <h4>Section {{ course.section }}</h4>
            </span>
        <?php endif;?>

        <?php $name = str_replace('-', ' ', $course->name)?>
            <div class="list-group-item">
                <span class="glyphicon glyphicon-film"></span> <?=ucwords($name)?>
                {% if !session.has('id') and !session.has('fb_user_id') %}
                    {% if course.free_preview == 1 %}
                        <a href="{{ url('product/preview') }}/{{ course.getProduct().slug }}/{{ course.id }}" class="pull-right label label-warning">Preview</a>
                    {% endif %}
                {% endif %}
                {% if course.getProductCourseMeta()|length !== 0 %}
                    <span class="pull-right label label-default">
                    {{ course.getProductCourseMeta()|length }} Resource(s)
                    </span>
                {% endif %}
            </div>
        <?php
        $set[] = $course->section;
        ?>
    {% endfor %}
    </div><!-- Close the last loop div -->
<?php else: ?>
    <div>
    There is no content ready for this course.
    </div>
<?php endif;?>
