package com.ExternalProcess;

import java.io.File;
import java.io.IOException;  
import java.io.InputStream;  
import java.io.OutputStream;
import java.lang.ProcessBuilder.Redirect;


public class ProcessRunner {
	
	private boolean isProcessing;
	
	public  ProcessRunner(){
		this.isProcessing = false;
	}
	
	public void setProcessing(boolean _is){
		isProcessing = _is;
	}
	
	public boolean getProcessing(){
		return isProcessing;
	}
 
    public void byRuntime(String[] command)
                throws IOException, InterruptedException {
    	 this.setProcessing(true);
        Runtime runtime = Runtime.getRuntime();
        Process process = runtime.exec(command);
        printStream(process);
    }

    public void byProcessBuilder(String[] command, String dir)  
                throws IOException,InterruptedException {
    	 this.setProcessing(true);
        ProcessBuilder builder = new ProcessBuilder(command);
   	 	 File workingDirectory = new File(dir);
   	 	 builder.directory(workingDirectory);
        Process process = builder.start();
        printStream(process);
    }

    private void printStream(Process process)
                throws IOException, InterruptedException {
        process.waitFor();
        try (InputStream psout = process.getInputStream()) {
            copy(psout, System.out);
        }
    }

    public void copy(InputStream input, OutputStream output) throws IOException {
        byte[] buffer = new byte[1024];
        int n = 0;
        while ((n = input.read(buffer)) != -1) {
            output.write(buffer, 0, n);
        }
        this.setProcessing(false);
    }
    
    public void byProcessBuilderimportCommoditydata(String command, String arg1, String arg2, String arg3, String firstday ,String endday,String dir)  
            throws IOException, InterruptedException {
    	
        
        this.setProcessing(true);
 		 ProcessBuilder builder = new ProcessBuilder(command, arg1, arg2, arg3,firstday, endday);
 	 	 File workingDirectory = new File(dir);
 	 	 builder.directory(workingDirectory);
 	 	 Process process = builder.start();
 	 	 printStream(process);
    }
    

    public void byProcessBuilderRedirectCrawlingraw(String command, String arg1, String arg2, String arg3, String arg4, String dir)  
            throws IOException, InterruptedException {
    	
        
        this.setProcessing(true);
 		 ProcessBuilder builder = new ProcessBuilder(command, arg1, arg2, arg3, arg4);
 	 	 File workingDirectory = new File(dir);
 	 	 builder.directory(workingDirectory);
 	 	 Process process = builder.start();
 	 	 printStream(process);
    }
    
    public void byProcessBuilderRedirectDir(String command, String arg1, String arg2, String dir)  
            throws IOException, InterruptedException {
    	
    	 File workingDirectory = new File(dir);
        ProcessBuilder builder = new ProcessBuilder(command, arg1, arg2);
        
        builder.directory(workingDirectory);
        builder.redirectOutput(Redirect.INHERIT);
        builder.redirectError(Redirect.INHERIT);
        builder.start();
    }
    
    public void byProcessBuilderRedirectDir(String[] command, String dir)  
            throws IOException, InterruptedException {
    	
    	 File workingDirectory = new File(dir);
        ProcessBuilder builder = new ProcessBuilder(command);
        
        builder.directory(workingDirectory);
        builder.redirectOutput(Redirect.INHERIT);
        builder.redirectError(Redirect.INHERIT);
        builder.start();
    }
    
    public void byProcessBuilderRedirect(String[] command)  
            throws IOException, InterruptedException {
    	
        ProcessBuilder builder = new ProcessBuilder(command);
        
        builder.redirectOutput(Redirect.INHERIT);
        builder.redirectError(Redirect.INHERIT);
        builder.start();
    }

}
