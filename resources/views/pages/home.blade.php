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
                        <a href="{{ route('page.show',$networkmain->id)}}" class="border border-gray-100 block md:flex rounded-3xl overflow-hidden transition duration-300 hover:shadow-lg">
                            <div class="md:w-1/2">
                               <img src="{{Voyager::image($networkmain->image)}}" alt="" class="">
                            </div>
                            <div class="md:w-1/2 p-4 md:p-7 my-8 ">
                                <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                <h2 class="font-bold text-pri text-xl md:text-2xl font-title py-3 transition duration-200 hover:text-sec ">{{$networkmain->title}}</h2>
                                <p class="text-gen pb-5">{{$networkmain->excerpt}}</p>
                              
                           
                           
                            </div>
                        </a>
                    </div>
                   <!-- end  -->
    
                    <!-- second row -->
                      <div class="blog-card ">
                           <a href="{{ route('page.show',$itsupport->id)}}">
                               <img src="{{Voyager::image($itsupport->image)}}" alt="">
                               <div class="px-4 pb-6">
                                   <h2 class="post-title">{{$itsupport->title}}</h2>
                                   <p class="text-gen pb-5">{{ Str::words($itsupport->excerpt, 20, ' ...') }}</p>
                                    <div class="flex items-center">
                               
                                 <div class="pl-3">
                                    <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                 </div>
                             </div>
                                
                                
                                </div>
                           </a>
                      </div>
    
                      <div class="blog-card ">
                        <a href="{{ route('page.show',$networkinst->id)}}">
                            <img src="{{Voyager::image($networkinst->image)}}" alt="">
                            <div class="px-4 pb-6">
                                <h2 class="post-title">{{$networkinst->title}}</h2>
                                <p class="text-gen pb-5">{{ Str::words($networkinst->excerpt, 20, ' ...') }}</p>
                               
                                <div class="flex items-center">
                               
                                    <div class="pl-3">
                                        <p class="px-3 py-1 bg-our-bg rounded text-sec text-xs inline animate-pulse ">See more...</p>
                                     </div>
                             </div>
                             
                             </div>
                        </a>
                   </div>
    
                   <div class="blog-card ">
                    <a href="{{ route('page.show',$phone->id)}}">
                        <img src="{{Voyager::image($phone->image)}}" alt="">
                        <div class="px-4 pb-6">
                            <h2 class="post-title">{{$phone->title}}</h2>
                            <p class="text-gen pb-5">{{ Str::words($phone->excerpt, 20, ' ...') }}</p>
                           
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
                        <a href="{{ route('page.show',$cloud->id)}}">
                            <img src="{{Voyager::image($cloud->image)}}" alt="" class="lg:h-72 w-full">
                            <div class="px-4 pb-6">
                                <h2 class="post-title">{{$cloud->title}}</h2>
                                <p class="text-gen pb-5">{{ Str::words($cloud->excerpt, 20, ' ...') }}</p>
                               
                           
                             
                             </div>
                             
                        </a>
                   </div>
    
                   <div class="blog-card mb-10 ">
                    <a href="{{ route('page.show',$cctv->id)}}">
                        <img src="{{Voyager::image($cctv->image)}}" alt="">
                        <div class="px-4 pb-6">
                            <h2 class="post-title">{{$cctv->title}}</h2>
                            <p class="text-gen pb-5">{{ Str::words($cctv->excerpt, 20, ' ...') }}</p>
                           
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