<section class="grid gap-3 lg:grid-cols-3">

    <div class="orange-card">
        <div class="panel-header">
            <div class="flex gap-2 items-center">
                <div class="avatar placeholder aspect-square w-12 rounded-full"></div>

                <div class="flex flex-col gap-3">
                    <p class="placeholder w-28"></p>
                    <p class="placeholder w-16"></p>
                </div>
            </div>
        </div>

    </div>

    <div class="purple-card p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
        <header class="w-full flex items-center justify-between">
            <p class="w-20 placeholder"></p>
            <span class="placeholder w-8"></span>
        </header>
        <div>
            <h1 class="h-10 w-10 placeholder"></h1>

        </div>


    </div>
    <div class="blue-card p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
        <header class="w-full flex items-center justify-between">
            <p class="w-20 placeholder"></p>
            <span class="placeholder w-8"></span>
        </header>
        <div>
            <h1 class="h-10 w-10 placeholder"></h1>

        </div>


    </div>

</section>





<div class="mt-8">
    <div class="card-header">
        <div class="card-title">
            My Courses
        </div>
    </div>
    <div class="flex flex-col gap-4">
        <section ng-repeat="allocation in [1,3,5,6,7,9,0]">
            <div class="card panel">
                <div class="flex items-center gap-1 w-full">
                    <div class="placeholder !rounded-full shrink-0 h-8 w-8 mr-2">
                    </div>
                    <h1 class="placeholder w-28"></h1>
                </div>
                <div class="mt-4 flex items-center gap-3 justify-between flex-wrap font-medium text-sm">
                    <div class="flex items-center flex-wrap gap-3">
                        <p class="placeholder w-10"></p>

                        <p class="placeholder w-10"></p>

                        <p class="placeholder w-12"></p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">

                        <p class="placeholder w-20 h-6"></p>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>