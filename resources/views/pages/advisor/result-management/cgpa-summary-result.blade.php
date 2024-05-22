<x-template title="Advisors - Results" nav="results">

    <x-wrapper>

        <div class="flex justify-between w-full mt-4">
            <form action="" class="flex items-center gap-2 w-full relative md:w-[32rem]">
                <div class="form-control">
                <label class="input-trailing" for="student-search">
                    <span class="material-symbols-rounded">search</span>
                </label>
                <input type="search" name="studentSearch" id="student-search" placeholder="Search..."
                    class="">
                
                <button type="submit" class="btn btn-primary open:transition rounded h-8">
                    Submit
                </button>
                </div>
            </form>

            <div>
                <button type="button" class="btn btn-primary transition" ng-click="print()">
                    Print
                </button>
            </div>
        </div>

        <div id="responsive-table print:visible" class="pb-4">
            <!-- Display this table if session = "all sessions" -->
            <table id="all-sessions" class="whitespace-nowrap">
                <thead style="text-align: center;">

                    <th class="w-5">S/N</th>
                    <th>Student Name</th>
                    <th>Reg. No.</th>
                    <th colspan="3">YEAR 1</th>
                    <th></th>
                    <th colspan="3">YEAR 2</th>
                    <th></th>
                    <th colspan="3">YEAR 3</th>
                    <th></th>
                    <th colspan="3">YEAR 4</th>
                    <th></th>
                    <th colspan="3">YEAR 5</th>
                    <th></th>
                </thead>
                <thead class="text-[.8rem]">
                    <th></th>
                    <th></th>
                    <th></th>

                    <th>TGP</th>
                    <th>TNU</th>
                    <th>CGPA</th>

                    <th></th>

                    <th>TGP</th>
                    <th>TNU</th>
                    <th>CGPA</th>

                    <th></th>

                    <th>TGP</th>
                    <th>TNU</th>
                    <th>CGPA</th>

                    <th></th>

                    <th>TGP</th>
                    <th>TNU</th>
                    <th>CGPA</th>

                    <th></th>

                    <th>TGP</th>
                    <th>TNU</th>
                    <th>CGPA</th>

                </thead>
                <tbody style="text-align: center;">
                    <tr>
                        <td style="text-align: left;">2</td>
                        <td>Amalagu Cosmos</td>
                        <td>20181112222</td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                    </tr>
                    <tr>
                        <td style="text-align: left;">2</td>
                        <td>Amalagu Cosmos</td>
                        <td>20181112222</td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                    </tr>
                    <tr>
                        <td style="text-align: left;">2</td>
                        <td>Amalagu Cosmos</td>
                        <td>20181112222</td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
                        <td></td>

                    </tr>

                </tbody>
            </table>
            <!--  -->
        </div>
    </x-wrapper>
</x-template>
