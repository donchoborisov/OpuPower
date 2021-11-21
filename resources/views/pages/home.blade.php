@extends('layouts.default')
@section('content')
    
    
    <body class="bg-hero-pattern" >

     


        <div class="container px-4 mx-auto max-w-5xl">
            {{-- <div class="text-center py-10 sm:py-14">
                
                <input type="search" name="" id="" class="bg-our-bg px-8 py-2 rounded-3xl shadow-inner focus:outline-none ring-sec focus:ring-1" placeholder="Search...">
            </div> --}}
            <section class="main mt-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12">
    
                    <!-- first row -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <a href="" class="border border-gray-100 block md:flex rounded-3xl overflow-hidden transition duration-300 hover:shadow-lg">
                            <div class="md:w-1/2">
                               <img src="{{asset('/img/network.jpg')}}" alt="" class="">
                            </div>
                            <div class="md:w-1/2 p-4 md:p-7 my-8 ">
                                <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                <h2 class="font-bold text-pri text-xl md:text-2xl font-title py-3 transition duration-200 hover:text-sec ">We offer IT Network maintenance and support services to businesses.</h2>
                                <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis nostrum voluptates, alias obcaecati adipisci nobis perferendis distinctio, rem nihil saepe eos ab! Veritatis iste quibusdam perspiciatis, consequuntur deleniti obcaecati quae nam laborum atque, debitis, facere exercitationem maxime! Eos ab dolorem ducimus reprehenderit vero omnis sed dolorum vel quae suscipit.</p>
                              
                           
                           
                            </div>
                        </a>
                    </div>
                   <!-- end  -->
    
                    <!-- second row -->
                      <div class="blog-card ">
                           <a href="#">
                               <img src="{{asset('/img/itsupport.jpg')}}" alt="">
                               <div class="px-4 pb-6">
                                   <h2 class="post-title">IT Support</h2>
                                   <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, amet quibusdam dolor natus esse suscipit molestiae asperiores voluptates quia atque.</p>
                                    <div class="flex items-center">
                               
                                 <div class="pl-3">
                                    <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                 </div>
                             </div>
                                
                                
                                </div>
                           </a>
                      </div>
    
                      <div class="blog-card ">
                        <a href="#">
                            <img src="{{asset('/img/installation.jpg')}}" alt="">
                            <div class="px-4 pb-6">
                                <h2 class="post-title">Network Installations</h2>
                                <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, amet quibusdam dolor natus esse suscipit molestiae asperiores voluptates quia atque.</p>
                               
                                <div class="flex items-center">
                               
                                    <div class="pl-3">
                                        <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                     </div>
                             </div>
                             
                             </div>
                        </a>
                   </div>
    
                   <div class="blog-card ">
                    <a href="#">
                        <img src="{{asset('/img/phone.jpg')}}" alt="">
                        <div class="px-4 pb-6">
                            <h2 class="post-title">Telephone Systems</h2>
                            <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, amet quibusdam dolor natus esse suscipit molestiae asperiores voluptates quia atque.</p>
                           
                            <div class="flex items-center">
                          
                             <div class="pl-3">
                                <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                             </div>
                         </div>
                         
                         </div>
                    </a>
               </div>


               


               
                    <!-- end row -->
                    <!-- third row -->
                    <div class="blog-card col-span-1 lg:col-span-2 mb-10">
                        <a href="#">
                            <img src="{{asset('/img/cloud2.jpg')}}" alt="" class="lg:h-72 w-full">
                            <div class="px-4 pb-6">
                                <h2 class="post-title">Cloud Solutions</h2>
                                <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, amet quibusdam dolor natus esse suscipit molestiae asperiores voluptates quia atque.</p>
                               
                           
                             
                             </div>
                        </a>
                   </div>
    
                   <div class="blog-card mb-10 ">
                    <a href="#">
                        <img src="{{asset('/img/cctv.jpg')}}" alt="">
                        <div class="px-4 pb-6">
                            <h2 class="post-title">CCTV Solutions</h2>
                            <p class="text-gen pb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, amet quibusdam dolor natus esse suscipit molestiae asperiores voluptates quia atque.</p>
                           
                            <div class="flex items-center">
                                <div class="pl-3">
                                    <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                 </div>
                           
                         </div>
                         
                         </div>
                    </a>
               </div>
    
                    
                    <!-- end row -->
    
                </div>
    
            </section>

            <div class="container px-4 mx-auto max-w-5xl">
              {{-- <div class="text-center py-10 sm:py-14">
                  <h1 class="text-3xl md:text-5xl font-bold font-title text-sec">
                      Our<br>
                      <span class="text-pri">
                        Partners
                      </span>
                  </h1>
                  
            </div> --}}

          </div>
           
            <section class="main">
           
            </section>



         
    
    
        </div>
        
@stop        